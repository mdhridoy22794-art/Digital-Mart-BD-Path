<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZiniPayService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://api.zinipay.com';
        $this->apiKey = Setting::get('zinipay_api_key') 
            ?: config('services.zinipay.api_key', env('ZINIPAY_API_KEY', '4e16b90fb1c397d0b5a4c4f32deab4349dad06172d2683fa'));
    }

    /**
     * Create a hosted payment invoice
     */
    public function createInvoice(array $params): array
    {
        $url = "{$this->baseUrl}/v1/payment/create";

        $payload = [
            'amount' => (float) $params['amount'],
            'cus_name' => $params['cus_name'] ?? 'Customer',
            'cus_email' => !empty($params['cus_email']) ? $params['cus_email'] : 'customer@digitalmart.com',
            'cus_phone' => $params['cus_phone'] ?? '',
            'redirect_url' => $params['redirect_url'] ?? route('payment.zinipay.callback'),
            'cancel_url' => $params['cancel_url'] ?? route('payment.zinipay.cancel'),
            'metadata' => $params['metadata'] ?? [],
        ];

        if (!empty($params['webhook_url'])) {
            $payload['webhook_url'] = $params['webhook_url'];
        }

        try {
            $response = Http::withHeaders([
                'zini-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(20)->post($url, $payload);

            $data = $response->json();

            Log::info('ZiniPay Create Invoice response', [
                'status' => $response->status(),
                'body' => $data,
            ]);

            if ($response->successful() && isset($data['payment_url'])) {
                return [
                    'success' => true,
                    'payment_url' => $data['payment_url'],
                    'val_id' => $data['val_id'] ?? null,
                    'message' => $data['message'] ?? 'Invoice created successfully.',
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'ZiniPay invoice generation failed.',
                'raw' => $data,
            ];

        } catch (\Exception $e) {
            Log::error('ZiniPay Create Invoice Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'পেমেন্ট গেটওয়ের সাথে সংযোগ স্থাপন করা যায়নি: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment status using val_id or invoice_id
     */
    public function verifyPayment(string $identifier, string $type = 'val_id'): array
    {
        $url = "{$this->baseUrl}/v1/payment/verify";

        $payload = [
            $type => $identifier,
        ];

        try {
            $response = Http::withHeaders([
                'zini-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(20)->post($url, $payload);

            $data = $response->json();

            Log::info('ZiniPay Verify Response', [
                'status' => $response->status(),
                'body' => $data,
            ]);

            if ($response->successful() && is_array($data)) {
                $status = strtoupper($data['status'] ?? 'PENDING');
                $isCompleted = ($status === 'COMPLETED' || $status === 'SUCCESS' || $status === 'PAID');

                return [
                    'success' => true,
                    'is_completed' => $isCompleted,
                    'status' => $status,
                    'transaction_id' => $data['transaction_id'] ?? null,
                    'sender_number' => $data['senderNumber'] ?? ($data['sender_phone'] ?? null),
                    'payment_method' => $data['payment_method'] ?? ($data['provider'] ?? 'zinipay'),
                    'amount' => (float) ($data['amount'] ?? 0),
                    'invoice_id' => $data['invoice_id'] ?? null,
                    'val_id' => $data['val_id'] ?? null,
                    'metadata' => $data['metadata'] ?? [],
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'is_completed' => false,
                'message' => $data['message'] ?? 'পেমেন্ট ভেরিফিকেশন ব্যর্থ হয়েছে।',
                'raw' => $data,
            ];

        } catch (\Exception $e) {
            Log::error('ZiniPay Verify Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'is_completed' => false,
                'message' => 'ভেরিফিকেশন সম্পন্ন করা সম্ভব হয়নি: ' . $e->getMessage(),
            ];
        }
    }
}
