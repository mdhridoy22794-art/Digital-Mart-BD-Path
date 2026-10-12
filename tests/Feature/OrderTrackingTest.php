<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderTrackingTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test completed order on /track shows completed status and delivered links
     */
    public function test_completed_order_displays_completed_badge_and_links(): void
    {
        $order = Order::create([
            'order_number' => 'DM-TRACK-CMPL',
            'product_id' => 1,
            'customer_name' => 'Completed Buyer',
            'customer_phone' => '01811112222',
            'amount' => 200,
            'payment_method' => 'bkash',
            'trx_id' => 'TRX_TRACK_1',
            'status' => 'completed',
            'delivered_link' => 'https://gemini.google.com/redeem-secret-code-xyz',
        ]);

        $response = $this->get(route('order.track', ['query' => $order->customer_phone]));

        $response->assertStatus(200);
        $response->assertSee('ডেলিভারি সম্পন্ন (Completed)');
        $response->assertSee('https://gemini.google.com/redeem-secret-code-xyz');
    }

    /**
     * Test pending order on /track displays pending status and NEVER exposes links
     */
    public function test_pending_order_displays_pending_status_without_links(): void
    {
        $order = Order::create([
            'order_number' => 'DM-TRACK-PEND',
            'product_id' => 1,
            'customer_name' => 'Pending Buyer',
            'customer_phone' => '01833334444',
            'amount' => 200,
            'payment_method' => 'zinipay',
            'trx_id' => null,
            'status' => 'pending',
            'delivered_link' => null,
        ]);

        $response = $this->get(route('order.track', ['query' => $order->customer_phone]));

        $response->assertStatus(200);
        $response->assertSee('পেমেন্ট প্রক্রিয়াধীন (Pending)');
        $response->assertDontSee('ডেলিভারি সম্পন্ন (Completed)');
        $response->assertDontSee('আপনার ডেলিভারিকৃত লিংকসমূহ');
    }

    /**
     * Test cancelled order on /track displays cancelled status and NEVER exposes links
     */
    public function test_cancelled_order_displays_cancelled_status_without_links(): void
    {
        $order = Order::create([
            'order_number' => 'DM-TRACK-CNCL',
            'product_id' => 1,
            'customer_name' => 'Cancelled Buyer',
            'customer_phone' => '01855556666',
            'amount' => 200,
            'payment_method' => 'zinipay',
            'trx_id' => null,
            'status' => 'cancelled',
            'delivered_link' => null,
        ]);

        $response = $this->get(route('order.track', ['query' => $order->customer_phone]));

        $response->assertStatus(200);
        $response->assertSee('বাতিল (Cancelled)');
        $response->assertDontSee('ডেলিভারি সম্পন্ন (Completed)');
        $response->assertDontSee('আপনার ডেলিভারিকৃত লিংকসমূহ');
    }

    /**
     * Test failed order on /track displays failed status and NEVER exposes links
     */
    public function test_failed_order_displays_failed_status_without_links(): void
    {
        $order = Order::create([
            'order_number' => 'DM-TRACK-FAIL',
            'product_id' => 1,
            'customer_name' => 'Failed Buyer',
            'customer_phone' => '01877778888',
            'amount' => 200,
            'payment_method' => 'zinipay',
            'trx_id' => null,
            'status' => 'failed',
            'delivered_link' => null,
        ]);

        $response = $this->get(route('order.track', ['query' => $order->customer_phone]));

        $response->assertStatus(200);
        $response->assertSee('ব্যর্থ (Failed)');
        $response->assertDontSee('ডেলিভারি সম্পন্ন (Completed)');
        $response->assertDontSee('আপনার ডেলিভারিকৃত লিংকসমূহ');
    }
}
