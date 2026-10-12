<?php

namespace Tests\Feature;

use App\Models\DigitalLink;
use App\Models\Order;
use App\Models\Product;
use App\Services\ZiniPayService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

class CheckoutAndZiniPayRegressionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test checkout initiation creates a pending order
     */
    public function test_checkout_initiation_creates_pending_order(): void
    {
        $mockZiniPay = Mockery::mock(ZiniPayService::class);
        $mockZiniPay->shouldReceive('createInvoice')
            ->once()
            ->andReturn([
                'success' => true,
                'payment_url' => 'https://secure.zinipay.com/digital-mart-bd/payment/test-inv-123',
                'val_id' => 'val-test-123',
            ]);

        $this->app->instance(ZiniPayService::class, $mockZiniPay);

        $product = Product::where('slug', 'gemini-pro-18m')->first() ?? Product::first();

        // Ensure at least 1 available link exists
        DigitalLink::firstOrCreate(
            ['link_url' => 'https://test-gemini-link.com', 'status' => 'available'],
            ['product_id' => $product->id]
        );

        $response = $this->postJson(route('payment.zinipay.init'), [
            'product_slug' => $product->slug,
            'quantity' => 1,
            'customer_name' => 'Regression Customer',
            'customer_phone' => '01999998888',
            'customer_email' => 'regression@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'payment_url' => 'https://secure.zinipay.com/digital-mart-bd/payment/test-inv-123',
        ]);

        $orderNumber = $response->json('order_number');
        $order = Order::where('order_number', $orderNumber)->first();

        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals('zinipay', $order->payment_method);
        $this->assertNull($order->delivered_link);
    }

    /**
     * Test payment verification callback fulfills order and assigns link
     */
    public function test_payment_callback_fulfills_order_with_link(): void
    {
        $product = Product::where('slug', 'gemini-pro-18m')->first() ?? Product::first();

        $link = DigitalLink::create([
            'product_id' => $product->id,
            'link_url' => 'https://activation.gemini.com/unique-test-code',
            'status' => 'available',
        ]);

        $order = Order::create([
            'order_number' => 'DM-CB-TEST-1',
            'product_id' => $product->id,
            'customer_name' => 'Callback Customer',
            'customer_phone' => '01988887777',
            'amount' => 200,
            'payment_method' => 'zinipay',
            'val_id' => 'val-cb-unique-123',
            'status' => 'pending',
        ]);

        $mockZiniPay = Mockery::mock(ZiniPayService::class);
        $mockZiniPay->shouldReceive('verifyPayment')
            ->with('val-cb-unique-123', 'val_id')
            ->once()
            ->andReturn([
                'success' => true,
                'is_completed' => true,
                'status' => 'COMPLETED',
                'transaction_id' => 'TRX_ZINI_TEST_99',
                'sender_number' => '01988887777',
                'payment_method' => 'bkash',
                'amount' => 200,
                'metadata' => [
                    'order_number' => $order->order_number,
                ],
                'raw' => [
                    'status' => 'COMPLETED',
                    'transaction_id' => 'TRX_ZINI_TEST_99',
                    'payment_method' => 'bkash',
                ],
            ]);

        $this->app->instance(ZiniPayService::class, $mockZiniPay);

        $response = $this->get(route('payment.zinipay.callback', ['val_id' => 'val-cb-unique-123']));

        $response->assertRedirect(route('order.success', ['order_number' => $order->order_number]));

        $freshOrder = $order->fresh();
        $this->assertEquals('completed', $freshOrder->status);
        $this->assertEquals('TRX_ZINI_TEST_99', $freshOrder->trx_id);
        $this->assertEquals('bkash', $freshOrder->payment_method);
        $this->assertEquals('bKash', $freshOrder->payment_method_name);
        $this->assertNotNull($freshOrder->delivered_link);

        // Verify the link allocated by FIFO is marked as sold and assigned to this order
        $assignedLink = DigitalLink::where('order_id', $freshOrder->id)->first();
        $this->assertNotNull($assignedLink);
        $this->assertEquals('sold', $assignedLink->status);
        $this->assertEquals($freshOrder->delivered_link, $assignedLink->link_url);
    }

    /**
     * Test ZiniPayService auto-fallback retries with alternate domain when domain mismatch occurs
     */
    public function test_service_domain_mismatch_triggers_alternate_domain_fallback(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://api.zinipay.com/v1/payment/create' => \Illuminate\Support\Facades\Http::sequence()
                ->push(['status' => false, 'message' => 'Domain mismatch: Redirect URL domain does not match brand\'s website URL.'], 400)
                ->push([
                    'status' => true,
                    'message' => 'Invoice created successfully via fallback.',
                    'payment_url' => 'https://secure.zinipay.com/digital-mart-bd/payment/fallback-success-123',
                    'val_id' => 'val-fallback-123',
                ], 201),
        ]);

        $service = new ZiniPayService();
        $result = $service->createInvoice([
            'amount' => 250,
            'cus_name' => 'Fallback Test',
            'cus_phone' => '01712345678',
        ]);

        $this->assertTrue($result['success']);
        $this->assertEquals('https://secure.zinipay.com/digital-mart-bd/payment/fallback-success-123', $result['payment_url']);
        $this->assertEquals('val-fallback-123', $result['val_id']);
    }
}
