<?php

namespace Tests\Unit;

use App\Models\Order;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    /**
     * Test direct payment methods (bKash, Nagad, Rocket)
     */
    public function test_direct_payment_methods_resolve_correctly(): void
    {
        $bkashOrder = new Order(['payment_method' => 'bkash']);
        $this->assertEquals('bKash', $bkashOrder->payment_method_name);
        $this->assertEquals('bKash', $bkashOrder->payment_method_badge['name']);
        $this->assertStringContainsString('pink', $bkashOrder->payment_method_badge['bg']);

        $nagadOrder = new Order(['payment_method' => 'nagad']);
        $this->assertEquals('Nagad', $nagadOrder->payment_method_name);
        $this->assertEquals('Nagad', $nagadOrder->payment_method_badge['name']);
        $this->assertStringContainsString('orange', $nagadOrder->payment_method_badge['bg']);

        $rocketOrder = new Order(['payment_method' => 'rocket']);
        $this->assertEquals('Rocket', $rocketOrder->payment_method_name);
        $this->assertEquals('Rocket', $rocketOrder->payment_method_badge['name']);
        $this->assertStringContainsString('indigo', $rocketOrder->payment_method_badge['bg']);
    }

    /**
     * Test ZiniPay with confirmed provider in payment_gateway_response
     */
    public function test_zinipay_with_confirmed_provider_resolves_correctly(): void
    {
        // 1. ZiniPay with bkash in payment_method key
        $order1 = new Order([
            'payment_method' => 'zinipay',
            'payment_gateway_response' => json_encode(['payment_method' => 'bkash']),
        ]);
        $this->assertEquals('bKash', $order1->payment_method_name);
        $this->assertEquals('bKash', $order1->payment_method_badge['name']);

        // 2. ZiniPay with nagad in provider key
        $order2 = new Order([
            'payment_method' => 'zinipay',
            'payment_gateway_response' => json_encode(['provider' => 'nagad']),
        ]);
        $this->assertEquals('Nagad', $order2->payment_method_name);
        $this->assertEquals('Nagad', $order2->payment_method_badge['name']);

        // 3. ZiniPay with rocket in channel key
        $order3 = new Order([
            'payment_method' => 'zinipay',
            'payment_gateway_response' => json_encode(['channel' => 'rocket']),
        ]);
        $this->assertEquals('Rocket', $order3->payment_method_name);
        $this->assertEquals('Rocket', $order3->payment_method_badge['name']);
    }

    /**
     * Test ZiniPay with unconfirmed / missing provider strictly shows ZiniPay without guessing
     */
    public function test_zinipay_without_confirmed_provider_strictly_shows_zinipay(): void
    {
        // Generic ZiniPay with no provider
        $order1 = new Order(['payment_method' => 'zinipay']);
        $this->assertEquals('ZiniPay', $order1->payment_method_name);
        $this->assertStringContainsString('ZiniPay', $order1->payment_method_badge['name']);
        $this->assertStringContainsString('purple', $order1->payment_method_badge['bg']);

        // ZiniPay with empty gateway response
        $order2 = new Order([
            'payment_method' => 'zinipay',
            'payment_gateway_response' => '',
        ]);
        $this->assertEquals('ZiniPay', $order2->payment_method_name);

        // ZiniPay with JSON containing no recognizable provider
        $order3 = new Order([
            'payment_method' => 'zinipay',
            'payment_gateway_response' => json_encode(['status' => 'COMPLETED', 'amount' => 200]),
        ]);
        $this->assertEquals('ZiniPay', $order3->payment_method_name);
        $this->assertStringContainsString('ZiniPay', $order3->payment_method_badge['name']);

        // ZiniPay with corrupt/invalid JSON
        $order4 = new Order([
            'payment_method' => 'zinipay',
            'payment_gateway_response' => '{invalid-json}',
        ]);
        $this->assertEquals('ZiniPay', $order4->payment_method_name);
    }

    /**
     * Test order status badge resolution for all statuses
     */
    public function test_order_status_badges(): void
    {
        $completedOrder = new Order(['status' => 'completed']);
        $this->assertStringContainsString('Completed', $completedOrder->status_badge['label']);
        $this->assertStringContainsString('emerald', $completedOrder->status_badge['bg']);

        $pendingOrder = new Order(['status' => 'pending']);
        $this->assertStringContainsString('Pending', $pendingOrder->status_badge['label']);
        $this->assertStringContainsString('amber', $pendingOrder->status_badge['bg']);

        $cancelledOrder = new Order(['status' => 'cancelled']);
        $this->assertStringContainsString('Cancelled', $cancelledOrder->status_badge['label']);
        $this->assertStringContainsString('slate', $cancelledOrder->status_badge['bg']);

        $failedOrder = new Order(['status' => 'failed']);
        $this->assertStringContainsString('Failed', $failedOrder->status_badge['label']);
        $this->assertStringContainsString('rose', $failedOrder->status_badge['bg']);
    }
}
