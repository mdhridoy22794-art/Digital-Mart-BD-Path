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
        'amount',
        'payment_method',
        'sender_phone',
        'trx_id',
        'screenshot_path',
        'status',
        'digital_link_id',
        'delivered_link',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function digitalLink()
    {
        return $this->belongsTo(DigitalLink::class);
    }
}
