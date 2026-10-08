<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaCapiService
{
    /**
     * Send server-side Purchase event to Meta Graph API (Conversions API)
     *
     * Protected by strict zero-failure boundaries:
     * - Fast 3-second timeout
     * - Wrapped in non-blocking try/catch
     * - Will never throw an exception or block order completion
     */
    public static function sendPurchaseEvent(Order $order, ?Request $request = null): bool
    {
        try {
            $accessToken = trim((string) Setting::get('meta_access_token', ''));
            $rawPixelIds = trim((string) Setting::get('meta_pixel_id', ''));

            if (empty($accessToken) || empty($rawPixelIds)) {
                return false;
            }

            // Prevent duplicate server sends for the same order
            $cacheKey = 'meta_capi_sent_' . $order->order_number;
            if (Cache::has($cacheKey)) {
                return true;
            }

            // Extract numeric pixel IDs
            $rawPids = preg_split('/[,;\r\n]+/', $rawPixelIds);
            $pixelIds = [];
            foreach ($rawPids as $p) {
                $cleaned = preg_replace('/[^0-9]/', '', trim($p));
                if (!empty($cleaned)) {
                    $pixelIds[] = $cleaned;
                }
            }

            if (empty($pixelIds)) {
                return false;
            }

            // Prepare client context
            $ip = $request ? $request->ip() : request()->ip();
            $userAgent = $request ? $request->userAgent() : request()->userAgent();

            // Hash user data according to Meta CAPI specification (SHA-256)
            $userData = [];

            // Phone normalization for Bangladesh (e.g., 017... -> 88017...)
            if (!empty($order->customer_phone)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', (string)$order->customer_phone);
                if (str_starts_with($cleanPhone, '01')) {
                    $cleanPhone = '88' . $cleanPhone;
                }
                if (!empty($cleanPhone)) {
                    $userData['ph'] = [hash('sha256', $cleanPhone)];
                }
            }

            // Email
            if (!empty($order->customer_email) && !str_ends_with($order->customer_email, '@digitalmart.com')) {
                $userData['em'] = [hash('sha256', strtolower(trim($order->customer_email)))];
            }

            // First Name
            if (!empty($order->customer_name)) {
                $userData['fn'] = [hash('sha256', strtolower(trim($order->customer_name)))];
            }

            if (!empty($ip)) {
                $userData['client_ip_address'] = $ip;
            }

            if (!empty($userAgent)) {
                $userData['client_user_agent'] = $userAgent;
            }

            // Event payload
            $productName = $order->product ? $order->product->name : 'Digital Subscription';
            $eventData = [
                'event_name' => 'Purchase',
                'event_time' => time(),
                'event_id' => $order->order_number,
                'event_source_url' => url('/order/success/' . $order->order_number),
                'action_source' => 'website',
                'user_data' => $userData,
                'custom_data' => [
                    'currency' => 'BDT',
                    'value' => (float) $order->amount,
                    'content_name' => $productName,
                    'content_type' => 'product',
                    'content_ids' => [(string) ($order->product_id ?: 1)],
                    'num_items' => (int) ($order->quantity ?: 1),
                    'order_id' => $order->order_number,
                ],
            ];

            // Dispatch to Meta Graph API for each configured Pixel ID
            foreach ($pixelIds as $pixelId) {
                $url = "https://graph.facebook.com/v19.0/{$pixelId}/events";
                
                $response = Http::withoutVerifying()
                    ->timeout(3)
                    ->post($url . '?access_token=' . urlencode($accessToken), [
                        'data' => [$eventData],
                    ]);

                if ($response->successful()) {
                    Log::info("Meta CAPI Purchase Event Sent for Order #{$order->order_number} (Pixel: {$pixelId})");
                } else {
                    Log::warning("Meta CAPI Response Error for Order #{$order->order_number}", [
                        'pixel_id' => $pixelId,
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]);
                }
            }

            Cache::put($cacheKey, true, 86400); // 24 hours
            return true;

        } catch (\Throwable $e) {
            // Completely silent failure ensures ZERO collateral damage to customer flow
            Log::warning('Meta CAPI Exception: ' . $e->getMessage(), [
                'order_number' => $order->order_number ?? null,
            ]);
            return false;
        }
    }
}
