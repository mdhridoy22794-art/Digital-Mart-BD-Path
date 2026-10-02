<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'subtitle',
        'regular_price',
        'offer_price',
        'features',
        'description',
        'badge',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'regular_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
    ];

    public function links()
    {
        return $this->hasMany(DigitalLink::class);
    }

    public function availableLinks()
    {
        return $this->hasMany(DigitalLink::class)->where('status', 'available');
    }

    public function soldLinks()
    {
        return $this->hasMany(DigitalLink::class)->where('status', 'sold');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getStockCountAttribute()
    {
        return $this->availableLinks()->count();
    }

    public function getImageUrlAttribute()
    {
        if (!empty($this->image_path) && file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }

        if (str_contains($this->slug, 'gemini')) {
            return asset('images/gemini_pro.jpg');
        } elseif (str_contains($this->slug, 'canva')) {
            return asset('images/canva_pro.jpg');
        } elseif (str_contains($this->slug, 'duolingo')) {
            return asset('images/duolingo.jpg');
        } elseif (str_contains($this->slug, 'landing-page')) {
            return asset('images/landing_pages.jpg');
        } elseif (str_contains($this->slug, 'capcut')) {
            return asset('images/capcut_pro.jpg');
        } elseif (str_contains($this->slug, 'chatgpt')) {
            return asset('images/chatgpt_pro.jpg');
        } elseif (str_contains($this->slug, 'claude')) {
            return asset('images/claude_ai.jpg');
        } elseif (str_contains($this->slug, 'office')) {
            return asset('images/office_365.jpg');
        }
        return asset('images/gemini_pro.jpg');
    }
}
