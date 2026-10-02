<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Product;
use App\Models\DigitalLink;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enforce 250 BDT offer price and active status for Gemini AI Pro
        $gemini = Product::where('slug', 'gemini-pro-18m')->first();
        if ($gemini) {
            $gemini->update([
                'offer_price' => 250.00,
                'regular_price' => 400.00,
                'is_active' => true,
            ]);
        } else {
            $gemini = Product::create([
                'slug' => 'gemini-pro-18m',
                'name' => 'Gemini AI Pro 18 Months (গুগল জেমিনাই প্রো)',
                'subtitle' => 'Google One AI Pro 18M with 5TB Storage & Veo3 Credits',
                'regular_price' => 400.00,
                'offer_price' => 250.00,
                'badge' => 'SAVE 50%',
                'description' => 'গুগল জেমিনাই প্রো ১৮ মাসের সম্পূর্ণ ওনার অ্যাকাউন্ট। আপনি আপনার পার্সোনাল জিমেইল অ্যাকাউন্টে অ্যাক্টিভ করতে পারবেন এবং সাথে ৫ জন ফ্যামিলি মেম্বার যুক্ত করে ৫টিবি গুগল ক্লাউড স্টোরেজ শেয়ার করার দারুণ সুবিধা পাবেন।',
                'features' => [
                    'সম্পূর্ণ ওনার অ্যাকাউন্ট (Personal Owner Account)',
                    'নিজের বর্তমান Gmail-এ সরাসরি ১০০% নিরাপদ অ্যাক্টিভেশন',
                    '৫ জন ফ্যামিলি মেম্বার যুক্ত করার সুবিধা (Family Sharing)',
                    '৫ টেরাবাইট (5TB) Google Cloud Storage (Drive, Gmail, Photos)',
                    'Flow & Veo 3 - প্রতি মাসে ১,০০০ ভিডিও জেনারেশন ক্রেডিট',
                    'Gemini Advanced 1.5 Pro & Gemini 2.0 ফ্ল্যাশ মডেল অ্যাক্সেস',
                    'ইনস্ট্যান্ট ১-সেকেন্ড স্বয়ংক্রিয় ইউনিক লিংক ডেলিভারি',
                    '২৪/৭ কাস্টমার সাপোর্ট ও লাইফটাইম গ্যারান্টি',
                ],
                'is_active' => true,
            ]);
        }

        // 2. Ensure available stock exists so the product is not marked as "Out of stock"
        $availableLinks = DigitalLink::where('product_id', $gemini->id)
            ->where('status', 'available')
            ->count();

        if ($availableLinks < 10) {
            // Seed stock links with unique timestamps & hash
            $needed = 20 - $availableLinks;
            for ($i = 1; $i <= $needed; $i++) {
                $hash = strtoupper(bin2hex(random_bytes(8)));
                $linkUrl = "https://serviceactivation.google.com/subscription/new/ACQpIIhV-GEMINI-PRO-18M-{$hash}";
                DigitalLink::firstOrCreate(
                    ['link_url' => $linkUrl],
                    [
                        'product_id' => $gemini->id,
                        'status' => 'available',
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
