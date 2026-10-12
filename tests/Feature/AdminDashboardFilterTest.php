<?php

namespace Tests\Feature;

use App\Models\DigitalLink;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminDashboardFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::first() ?? User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@digitalmartbd.xyz',
            'password' => bcrypt('password123'),
        ]);
    }

    /**
     * Test that Admin Dashboard recent orders ONLY includes completed orders with delivered links.
     */
    public function test_dashboard_shows_only_verified_successful_orders(): void
    {
        // 1. Create a genuinely completed order with delivered_link
        $completedOrder = Order::create([
            'order_number' => 'DM-TEST-COMPLETED',
            'product_id' => 1,
            'customer_name' => 'Verified Buyer',
            'customer_phone' => '01711111111',
            'amount' => 200,
            'payment_method' => 'bkash',
            'trx_id' => 'TRX_COMPLETED_1',
            'status' => 'completed',
            'delivered_link' => 'https://gemini.google.com/test-link-1',
        ]);

        // 2. Create a pending order (unverified, customer never paid)
        $pendingOrder = Order::create([
            'order_number' => 'DM-TEST-PENDING',
            'product_id' => 1,
            'customer_name' => 'Unverified Customer',
            'customer_phone' => '01722222222',
            'amount' => 200,
            'payment_method' => 'zinipay',
            'status' => 'pending',
            'delivered_link' => null,
        ]);

        // 3. Create a cancelled order
        $cancelledOrder = Order::create([
            'order_number' => 'DM-TEST-CANCELLED',
            'product_id' => 1,
            'customer_name' => 'Cancelled Customer',
            'customer_phone' => '01733333333',
            'amount' => 200,
            'payment_method' => 'zinipay',
            'status' => 'cancelled',
            'delivered_link' => null,
        ]);

        // 4. Create an anomalous completed order without delivered_link
        $anomalyOrder = Order::create([
            'order_number' => 'DM-TEST-ANOMALY',
            'product_id' => 1,
            'customer_name' => 'No Link Customer',
            'customer_phone' => '01744444444',
            'amount' => 200,
            'payment_method' => 'zinipay',
            'status' => 'completed',
            'delivered_link' => null,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Verify recentOrders in view data
        $recentOrders = $response->viewData('recentOrders');

        // Completed order with delivered link MUST be present
        $this->assertTrue($recentOrders->contains('id', $completedOrder->id));

        // Pending, Cancelled, and Anomaly orders MUST NOT be present in recentOrders
        $this->assertFalse($recentOrders->contains('id', $pendingOrder->id), 'Pending order must not appear in recentOrders');
        $this->assertFalse($recentOrders->contains('id', $cancelledOrder->id), 'Cancelled order must not appear in recentOrders');
        $this->assertFalse($recentOrders->contains('id', $anomalyOrder->id), 'Order without delivered link must not appear in recentOrders');
    }

    /**
     * Test payment badge rendering on dashboard
     */
    public function test_dashboard_renders_payment_badges_properly(): void
    {
        // bKash order
        Order::create([
            'order_number' => 'DM-TEST-BKASH',
            'customer_name' => 'Bkash User',
            'customer_phone' => '01755555555',
            'amount' => 200,
            'payment_method' => 'bkash',
            'trx_id' => 'TRX_BK_1',
            'status' => 'completed',
            'delivered_link' => 'https://test-link.com/1',
        ]);

        // ZiniPay order without provider (must display ZiniPay, NOT Nagad)
        Order::create([
            'order_number' => 'DM-TEST-ZINI',
            'customer_name' => 'Zini User',
            'customer_phone' => '01766666666',
            'amount' => 200,
            'payment_method' => 'zinipay',
            'trx_id' => 'TRX_ZN_1',
            'status' => 'completed',
            'delivered_link' => 'https://test-link.com/2',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('bKash');
        $response->assertSee('ZiniPay');
    }
}
