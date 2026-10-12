<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'product_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'quantity',
        'amount',
        'payment_method',
        'sender_phone',
        'trx_id',
        'invoice_id',
        'val_id',
        'screenshot_path',
        'status',
        'digital_link_id',
        'delivered_link',
        'admin_notes',
        'payment_gateway_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function digitalLink()
    {
        return $this->belongsTo(DigitalLink::class);
    }

    public function digitalLinks()
    {
        return $this->hasMany(DigitalLink::class)->orderBy('id', 'asc');
    }

    /**
     * Get the resolved, verified payment method name without guesswork.
     */
    public function getPaymentMethodNameAttribute(): string
    {
        $rawMethod = strtolower(trim((string) $this->payment_method));

        // 1. If direct payment_method specifies a recognized provider
        if ($rawMethod === 'bkash') {
            return 'bKash';
        }
        if ($rawMethod === 'nagad') {
            return 'Nagad';
        }
        if ($rawMethod === 'rocket') {
            return 'Rocket';
        }

        // 2. If processed via ZiniPay, check parsed gateway response
        if (!empty($this->payment_gateway_response)) {
            $parsed = is_array($this->payment_gateway_response)
                ? $this->payment_gateway_response
                : json_decode((string) $this->payment_gateway_response, true);

            if (is_array($parsed)) {
                $subProvider = strtolower(trim(
                    (string) ($parsed['payment_method']
                        ?? $parsed['provider']
                        ?? $parsed['channel']
                        ?? $parsed['payment_type']
                        ?? '')
                ));

                if ($subProvider === 'bkash') {
                    return 'bKash';
                }
                if ($subProvider === 'nagad') {
                    return 'Nagad';
                }
                if ($subProvider === 'rocket') {
                    return 'Rocket';
                }
            }
        }

        // 3. Fallback for ZiniPay or generic without reliable provider
        if ($rawMethod === 'zinipay' || empty($rawMethod)) {
            return 'ZiniPay';
        }

        return ucfirst($rawMethod);
    }

    /**
     * Get UI badge metadata for the resolved payment method.
     */
    public function getPaymentMethodBadgeAttribute(): array
    {
        $name = $this->payment_method_name;

        return match ($name) {
            'bKash' => [
                'name' => 'bKash',
                'bg' => 'bg-pink-50',
                'text' => 'text-pink-700',
                'border' => 'border-pink-200',
                'dot' => 'bg-pink-500',
            ],
            'Nagad' => [
                'name' => 'Nagad',
                'bg' => 'bg-orange-50',
                'text' => 'text-orange-700',
                'border' => 'border-orange-200',
                'dot' => 'bg-orange-500',
            ],
            'Rocket' => [
                'name' => 'Rocket',
                'bg' => 'bg-indigo-50',
                'text' => 'text-indigo-700',
                'border' => 'border-indigo-200',
                'dot' => 'bg-indigo-500',
            ],
            default => [
                'name' => '⚡ ZiniPay Auto',
                'bg' => 'bg-purple-50',
                'text' => 'text-purple-700',
                'border' => 'border-purple-200',
                'dot' => 'bg-purple-600',
            ],
        };
    }

    /**
     * Get UI badge metadata and label for order status.
     */
    public function getStatusBadgeAttribute(): array
    {
        $status = strtolower(trim((string) $this->status));

        return match ($status) {
            'completed' => [
                'label' => 'ডেলিভারি সম্পন্ন (Completed)',
                'bg' => 'bg-emerald-100',
                'text' => 'text-emerald-800',
                'border' => 'border-emerald-200',
                'dot' => 'bg-emerald-500',
            ],
            'pending' => [
                'label' => 'পেমেন্ট প্রক্রিয়াধীন (Pending)',
                'bg' => 'bg-amber-100',
                'text' => 'text-amber-800',
                'border' => 'border-amber-200',
                'dot' => 'bg-amber-500',
            ],
            'cancelled' => [
                'label' => 'বাতিল (Cancelled)',
                'bg' => 'bg-slate-100',
                'text' => 'text-slate-700',
                'border' => 'border-slate-300',
                'dot' => 'bg-slate-500',
            ],
            'failed' => [
                'label' => 'ব্যর্থ (Failed)',
                'bg' => 'bg-rose-100',
                'text' => 'text-rose-800',
                'border' => 'border-rose-200',
                'dot' => 'bg-rose-500',
            ],
            default => [
                'label' => ucfirst($status),
                'bg' => 'bg-slate-100',
                'text' => 'text-slate-700',
                'border' => 'border-slate-300',
                'dot' => 'bg-slate-500',
            ],
        };
    }
}
