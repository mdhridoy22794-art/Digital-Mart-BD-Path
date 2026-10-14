<?php

namespace Tests\Feature;

use App\Models\DigitalLink;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\ZiniPayService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminOrderVerifyTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::first() ?? User::create([
            'name' => 'Admin Test',
            'email' => 'admin_verify_test@digitalmartbd.com',
            'password' => bcrypt('password123'),
        ]);

        $this->product = Product::firstOrCreate(
            ['slug' => 'gemini-pro-18m'],
            [
                'name' => 'Gemini AI Pro 18 Months',
                'regular_price' => 500,
                'offer_price' => 200,
                'is_active' => true,
            ]
        );
    }

    public function test_guest_cannot_verify_order()
    {
        $order = Order::create([
            'order_number' => 'DM-TESTGUEST',
            'product_id' => $this->product->id,
            'customer_name' => 'Guest Target',
            'customer_phone' => '01700000001',
            'amount' => 200,
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.orders.verify', $order->id));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_verify_and_fulfill_order_via_zinipay()
    {
        // Ensure at least one link is available
        DigitalLink::create([
            'product_id' => $this->product->id,
            'link_url' => 'https://example.com/sub/TESTLINK1',
            'status' => 'available',
        ]);

        $order = Order::create([
            'order_number' => 'DM-ZPVER1',
            'product_id' => $this->product->id,
            'customer_name' => 'Sabuz Miya',
            'customer_phone' => '01714343018',
            'amount' => 200,
            'status' => 'pending',
            'invoice_id' => 'test-invoice-123',
            'val_id' => 'test-val-123',
        ]);

        $mockZiniPay = $this->createMock(ZiniPayService::class);
        $mockZiniPay->expects($this->once())
            ->method('verifyPayment')
            ->willReturn([
                'success' => true,
                'is_completed' => true,
                'status' => 'COMPLETED',
                'transaction_id' => 'DJE1W11YDP',
                'sender_number' => '01714343018',
                'payment_method' => 'BKASH',
                'raw' => ['test' => true],
            ]);

        $this->app->instance(ZiniPayService::class, $mockZiniPay);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.verify', $order->id), [
            'action_type' => 'zinipay',
        ]);

        $response->assertRedirect(route('admin.orders'));
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('completed', $order->status);
        $this->assertEquals('DJE1W11YDP', $order->trx_id);
        $this->assertEquals('BKASH', $order->payment_method);
        $this->assertNotEmpty($order->delivered_link);
        $this->assertNotNull($order->digital_link_id);

        $soldLink = DigitalLink::find($order->digital_link_id);
        $this->assertEquals('sold', $soldLink->status);
        $this->assertEquals($order->id, $soldLink->order_id);
    }

    public function test_admin_can_manually_verify_and_fulfill_order()
    {
        DigitalLink::create([
            'product_id' => $this->product->id,
            'link_url' => 'https://example.com/sub/MANUALLINK1',
            'status' => 'available',
        ]);

        $order = Order::create([
            'order_number' => 'DM-MAN01',
            'product_id' => $this->product->id,
            'customer_name' => 'Bappy Rana',
            'customer_phone' => '01780891556',
            'amount' => 200,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.verify', $order->id), [
            'action_type' => 'manual',
            'trx_id' => 'DJE8W2L238',
            'payment_method' => 'bKash',
        ]);

        $response->assertRedirect(route('admin.orders'));
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('completed', $order->status);
        $this->assertEquals('DJE8W2L238', $order->trx_id);
        $this->assertNotEmpty($order->delivered_link);
        $this->assertNotNull($order->digital_link_id);

        $soldLink = DigitalLink::find($order->digital_link_id);
        $this->assertEquals('sold', $soldLink->status);
    }

    public function test_admin_sync_pending_orders_fulfills_completed_zinipay_orders()
    {
        DigitalLink::create([
            'product_id' => $this->product->id,
            'link_url' => 'https://example.com/sub/SYNCLINK1',
            'status' => 'available',
        ]);

        $order = Order::create([
            'order_number' => 'DM-SYNC01',
            'product_id' => $this->product->id,
            'customer_name' => 'Sync Customer',
            'customer_phone' => '01799999999',
            'amount' => 200,
            'status' => 'pending',
            'invoice_id' => 'test-sync-invoice',
        ]);

        $mockZiniPay = $this->createMock(ZiniPayService::class);
        $mockZiniPay->method('verifyPayment')
            ->willReturn([
                'success' => true,
                'is_completed' => true,
                'status' => 'COMPLETED',
                'transaction_id' => 'TXN-SYNC-999',
                'sender_number' => '01799999999',
                'payment_method' => 'bKash',
                'raw' => [],
            ]);

        $this->app->instance(ZiniPayService::class, $mockZiniPay);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.sync-pending'));

        $response->assertRedirect(route('admin.orders'));
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('completed', $order->status);
        $this->assertEquals('TXN-SYNC-999', $order->trx_id);
        $this->assertNotEmpty($order->delivered_link);
    }
}
