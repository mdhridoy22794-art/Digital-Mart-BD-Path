<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderSuccessStatusTest extends TestCase
{
    use DatabaseTransactions;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::first() ?? Product::create([
            'slug' => 'gemini-pro-18m',
            'name' => 'Gemini AI Pro',
            'regular_price' => 400,
            'offer_price' => 250,
            'is_active' => true,
        ]);
    }

    /**
     * Test completed order on /order/{order_number}/success shows success banner and delivered links
     */
    public function test_completed_order_displays_success_banner_and_delivered_links(): void
    {
        $order = Order::create([
            'order_number' => 'DM-SUCC-COMP',
            'product_id' => $this->product->id,
            'customer_name' => 'Happy Buyer',
            'customer_phone' => '01811223344',
            'amount' => 250,
            'payment_method' => 'bkash',
            'trx_id' => 'TRX_SUCCESS_1',
            'status' => 'completed',
            'delivered_link' => 'https://gemini.google.com/activation-key-101',
        ]);

        $response = $this->get(route('order.success', ['order_number' => $order->order_number]));

        $response->assertStatus(200);
        $response->assertSee('পেমেন্ট সফল ও লিংক ডেলিভারি!');
        $response->assertSee('https://gemini.google.com/activation-key-101');
        $response->assertSee('bKash');
    }

    /**
     * Test pending order on /order/{order_number}/success displays pending banner and never exposes links
     */
    public function test_pending_order_displays_pending_banner_without_links(): void
    {
        $order = Order::create([
            'order_number' => 'DM-SUCC-PEND',
            'product_id' => $this->product->id,
            'customer_name' => 'Waiting Buyer',
            'customer_phone' => '01855667788',
            'amount' => 250,
            'payment_method' => 'zinipay',
            'status' => 'pending',
            'delivered_link' => null,
        ]);

        $response = $this->get(route('order.success', ['order_number' => $order->order_number]));

        $response->assertStatus(200);
        $response->assertSee('পেমেন্ট প্রক্রিয়াধীন (Pending)');
        $response->assertDontSee('পেমেন্ট সফল ও লিংক ডেলিভারি!');
        $response->assertDontSee('আপনার অ্যাক্টিভেশন লিংকসমূহ');
    }

    /**
     * Test cancelled order on /order/{order_number}/success displays cancelled banner without links
     */
    public function test_cancelled_order_displays_cancelled_banner_without_links(): void
    {
        $order = Order::create([
            'order_number' => 'DM-SUCC-CNCL',
            'product_id' => $this->product->id,
            'customer_name' => 'Cancelled Buyer',
            'customer_phone' => '01899001122',
            'amount' => 250,
            'payment_method' => 'zinipay',
            'status' => 'cancelled',
            'delivered_link' => null,
        ]);

        $response = $this->get(route('order.success', ['order_number' => $order->order_number]));

        $response->assertStatus(200);
        $response->assertSee('বাতিল (Cancelled)');
        $response->assertDontSee('পেমেন্ট সফল ও লিংক ডেলিভারি!');
        $response->assertDontSee('আপনার অ্যাক্টিভেশন লিংকসমূহ');
    }

    /**
     * Test failed order on /order/{order_number}/success displays failed banner without links
     */
    public function test_failed_order_displays_failed_banner_without_links(): void
    {
        $order = Order::create([
            'order_number' => 'DM-SUCC-FAIL',
            'product_id' => $this->product->id,
            'customer_name' => 'Failed Buyer',
            'customer_phone' => '01844332211',
            'amount' => 250,
            'payment_method' => 'zinipay',
            'status' => 'failed',
            'delivered_link' => null,
        ]);

        $response = $this->get(route('order.success', ['order_number' => $order->order_number]));

        $response->assertStatus(200);
        $response->assertSee('পেমেন্ট ব্যর্থ (Failed)');
        $response->assertDontSee('পেমেন্ট সফল ও লিংক ডেলিভারি!');
        $response->assertDontSee('আপনার অ্যাক্টিভেশন লিংকসমূহ');
    }
}
