<?php

namespace App\Http\Controllers;

use App\Models\DigitalLink;
use App\Models\Order;
use App\Models\Product;
use App\Services\ZiniPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ZiniPayController extends Controller
{
    protected ZiniPayService $ziniPayService;

    public function __construct(ZiniPayService $ziniPayService)
    {
        $this->ziniPayService = $ziniPayService;
    }

    /**
     * Initialize ZiniPay Payment
     */
    public function initPayment(Request $request)
    {
        $validated = $request->validate([
            'product_slug' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1|max:100',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:100',
        ]);

        $slug = $validated['product_slug'] ?? 'gemini-pro-18m';
        $product = Product::where('slug', $slug)->first();

        if (!$product || !$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'দুঃখিত! এই প্রোডাক্টটির স্টক বর্তমানে শেষ হয়ে গেছে।',
            ], 422);
        }

        $quantity = (int) ($validated['quantity'] ?? 1);
        if ($quantity < 1) $quantity = 1;

        // Check available stock
        $availableStock = DigitalLink::where('product_id', $product->id)
            ->where('status', 'available')
            ->count();

        if ($availableStock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => "দুঃখিত! আপনি {$quantity}টি লিংক অর্ডার করতে চেয়েছেন, কিন্তু স্টকে বর্তমানে {$availableStock}টি লিংক অবশিষ্ট রয়েছে।",
            ], 422);
        }

        $totalAmount = $product->offer_price * $quantity;
        $orderNumber = 'DM-' . strtoupper(Str::random(6));

        $email = !empty($validated['customer_email']) 
            ? $validated['customer_email'] 
            : preg_replace('/[^0-9]/', '', $validated['customer_phone']) . '@digitalmart.com';

        // Create pending order
        $order = Order::create([
            'order_number' => $orderNumber,
            'product_id' => $product->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $email,
            'quantity' => $quantity,
            'amount' => $totalAmount,
            'payment_method' => 'zinipay',
            'status' => 'pending',
        ]);

        // Request payment invoice from ZiniPay
        $paymentResult = $this->ziniPayService->createInvoice([
            'amount' => $totalAmount,
            'cus_name' => $order->customer_name,
            'cus_email' => $order->customer_email,
            'cus_phone' => $order->customer_phone,
            'redirect_url' => route('payment.zinipay.callback'),
            'cancel_url' => route('payment.zinipay.cancel', ['order_number' => $order->order_number]),
            'webhook_url' => route('payment.zinipay.webhook'),
            'metadata' => [
                'order_number' => $order->order_number,
                'order_id' => $order->id,
                'customer_phone' => $order->customer_phone,
                'quantity' => $quantity,
            ],
        ]);

        if (!$paymentResult['success'] || empty($paymentResult['payment_url'])) {
            $order->update([
                'status' => 'cancelled',
                'admin_notes' => 'ZiniPay invoice generation error: ' . ($paymentResult['message'] ?? 'Unknown error'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'পেমেন্ট গেটওয়ে তৈরিতে সমস্যা হয়েছে: ' . ($paymentResult['message'] ?? 'অনুগ্রহ করে পুনরায় চেষ্টা করুন'),
            ], 400);
        }

        // Store val_id and invoice_id
        $valId = $paymentResult['val_id'] ?? null;
        $invoiceId = null;
        if (preg_match('/payment\/([a-zA-Z0-9\-]+)/', $paymentResult['payment_url'], $matches)) {
            $invoiceId = $matches[1];
        }

        $order->update([
            'val_id' => $valId,
            'invoice_id' => $invoiceId,
        ]);

        return response()->json([
            'success' => true,
            'payment_url' => $paymentResult['payment_url'],
            'order_number' => $order->order_number,
        ]);
    }

    /**
     * Handle Customer Redirect Callback from ZiniPay
     */
    public function handleCallback(Request $request)
    {
        Log::info('ZiniPay Callback Query:', $request->all());

        $valId = $request->input('val_id');
        $invoiceId = $request->input('invoice_id');

        if (!$valId && !$invoiceId) {
            return redirect()->route('home')->with('error', 'অবৈধ পেমেন্ট রেসপন্স পাওয়া গেছে।');
        }

        // Verify payment
        $verifyResult = $valId 
            ? $this->ziniPayService->verifyPayment($valId, 'val_id')
            : $this->ziniPayService->verifyPayment($invoiceId, 'invoice_id');

        if (!$verifyResult['success']) {
            return redirect()->route('home')->with('error', 'পেমেন্ট ভেরিফিকেশন করা সম্ভব হয়নি: ' . ($verifyResult['message'] ?? ''));
        }

        // Find corresponding order
        $order = null;
        if (!empty($verifyResult['metadata']['order_number'])) {
            $order = Order::where('order_number', $verifyResult['metadata']['order_number'])->first();
        }

        if (!$order && $valId) {
            $order = Order::where('val_id', $valId)->first();
        }

        if (!$order && $invoiceId) {
            $order = Order::where('invoice_id', $invoiceId)->first();
        }

        if (!$order && !empty($verifyResult['invoice_id'])) {
            $order = Order::where('invoice_id', $verifyResult['invoice_id'])->first();
        }

        if (!$order) {
            Log::error('ZiniPay Callback: Order not found for verified payment', $verifyResult);
            return redirect()->route('home')->with('error', 'আপনার অর্ডারটি সিস্টেমে খুঁজে পাওয়া যায়নি। অনুগ্রহ করে কাস্টমার সাপোর্টে যোগাযোগ করুন।');
        }

        // Check if verified as completed
        if ($verifyResult['is_completed']) {
            $this->fulfillOrder($order, $verifyResult);
            return redirect()->route('order.success', ['order_number' => $order->order_number]);
        }

        return redirect()->route('product.details', $order->product->slug ?? 'gemini-pro-18m')
            ->with('error', 'আপনার পেমেন্টটি এখনও সম্পন্ন হয়নি (স্ট্যাটাস: ' . ($verifyResult['status'] ?? 'PENDING') . ')।');
    }

    /**
     * Handle Server-to-Server IPN Webhook from ZiniPay
     */
    public function handleWebhook(Request $request)
    {
        Log::info('ZiniPay Webhook Incoming:', $request->all());

        $valId = $request->input('val_id');
        $invoiceId = $request->input('invoice_id');

        if (!$valId && !$invoiceId) {
            return response()->json(['status' => 'ignored', 'message' => 'No identifier provided'], 400);
        }

        $verifyResult = $valId 
            ? $this->ziniPayService->verifyPayment($valId, 'val_id')
            : $this->ziniPayService->verifyPayment($invoiceId, 'invoice_id');

        if ($verifyResult['success'] && $verifyResult['is_completed']) {
            $order = null;
            if (!empty($verifyResult['metadata']['order_number'])) {
                $order = Order::where('order_number', $verifyResult['metadata']['order_number'])->first();
            }
            if (!$order && $valId) {
                $order = Order::where('val_id', $valId)->first();
            }
            if (!$order && $invoiceId) {
                $order = Order::where('invoice_id', $invoiceId)->first();
            }

            if ($order) {
                $this->fulfillOrder($order, $verifyResult);
                return response()->json(['status' => 'success', 'message' => 'Order fulfilled successfully']);
            }
        }

        return response()->json(['status' => 'received']);
    }

    /**
     * Handle Customer Cancellation
     */
    public function handleCancel(Request $request)
    {
        $orderNumber = $request->input('order_number');
        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order && $order->status === 'pending') {
                $order->update(['status' => 'cancelled', 'admin_notes' => 'Customer cancelled at payment gateway']);
            }
        }

        return redirect()->route('home')->with('info', 'পেমেন্ট বাতিল করা হয়েছে। আপনি চাইলে পুনরায় অর্ডার করতে পারেন।');
    }

    /**
     * Strict serial FIFO Order Fulfillment with concurrency safety
     */
    protected function fulfillOrder(Order $order, array $gatewayData): Order
    {
        // Avoid double fulfillment
        if ($order->status === 'completed') {
            return $order;
        }

        return DB::transaction(function () use ($order, $gatewayData) {
            $freshOrder = Order::where('id', $order->id)->lockForUpdate()->first();
            if ($freshOrder->status === 'completed') {
                return $freshOrder;
            }

            $quantity = $freshOrder->quantity ?: 1;

            // Pick links in serial FIFO order (id ASC)
            $links = DigitalLink::where('product_id', $freshOrder->product_id)
                ->where('status', 'available')
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->take($quantity)
                ->get();

            if ($links->count() > 0) {
                $deliveredText = $links->pluck('link_url')->implode("\n");
                $firstLinkId = $links->first()->id;

                foreach ($links as $link) {
                    $link->update([
                        'status' => 'sold',
                        'order_id' => $freshOrder->id,
                        'delivered_to_phone' => $freshOrder->customer_phone,
                        'delivered_at' => now(),
                    ]);
                }

                $freshOrder->digital_link_id = $firstLinkId;
                $freshOrder->delivered_link = $deliveredText;
            }

            $trxId = $gatewayData['transaction_id'] ?? ('ZINI-' . strtoupper(Str::random(8)));
            $senderPhone = $gatewayData['sender_number'] ?? null;
            $payMethod = $gatewayData['payment_method'] ?? 'zinipay';

            $freshOrder->update([
                'status' => 'completed',
                'trx_id' => $trxId,
                'sender_phone' => $senderPhone,
                'payment_method' => $payMethod,
                'payment_gateway_response' => json_encode($gatewayData['raw'] ?? []),
            ]);

            return $freshOrder;
        });
    }
}
