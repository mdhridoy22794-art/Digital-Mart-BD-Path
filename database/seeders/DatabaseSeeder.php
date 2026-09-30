<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\DigitalLink;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with full e-commerce store catalog.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@digitalmartbd.com'],
            [
                'name' => 'Digital Mart BD Admin',
                'password' => Hash::make('Admin#Mart2026'),
            ]
        );

        // 2. Default Settings
        $settings = [
            'site_title' => 'Digital Mart BD - সর্ববৃহৎ ডিজিটাল সাবস্ক্রিপশন ও সফটওয়্যার স্টোর',
            'announcement_text' => '🔥 মেগা সেল চলছে! গুগল জেমিনাই প্রো ১৮ মাস মাত্র ২০০ টাকায়! পেমেন্ট করলেই ১ সেকেন্ডে ইনস্ট্যান্ট লিংক ডেলিভারি!',
            'bkash_number' => '01934779775',
            'nagad_number' => '01934779775',
            'whatsapp_number' => '+8801934779775',
            'facebook_url' => 'https://www.facebook.com/digitalmartbd.store',
            'meta_pixel_id' => '',
            'custom_header_script' => '',
            'zinipay_api_key' => '4e16b90fb1c397d0b5a4c4f32deab4349dad06172d2683fa',
            'zinipay_status' => 'active',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 3. Products Catalog (Gemini Pro IN STOCK, others OUT OF STOCK as requested)
        $products = [
            [
                'slug' => 'gemini-pro-18m',
                'name' => 'Gemini AI Pro 18 Months (গুগল জেমিনাই প্রো)',
                'subtitle' => 'Google One AI Pro 18M with 5TB Storage & Veo3 Credits',
                'regular_price' => 400.00,
                'offer_price' => 200.00,
                'badge' => 'SAVE 50%',
                'description' => 'গুগল জেমিনাই প্রো ১৮ মাসের সম্পূর্ণ ওনার অ্যাকাউন্ট। আপনি আপনার পার্সোনাল জিমেইল অ্যাকাউন্টে অ্যাক্টিভ করতে পারবেন এবং সাথে ৫ জন ফ্যামিলি মেম্বার যুক্ত করে ৫টিবি গুগল ক্লাউড স্টোরেজ শেয়ার করার দারুণ সুবিধা পাবেন। সাথে থাকছে Flow/Veo3 প্রতি মাসে ১,০০০ ক্রেডিট। এটি সম্পূর্ণ অটোমেটিক ডেলিভারি সিস্টেম—পেমেন্ট করার সাথে সাথেই স্ক্রিনে নতুন ইউনিক লিংক চলে আসবে।',
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
            ],
            [
                'slug' => 'canva-pro-1-year',
                'name' => 'Canva Pro Subscription (ক্যানভা প্রো ১ বছর)',
                'subtitle' => 'Premium Brand Kit, 100M+ Assets & AI Magic Studio',
                'regular_price' => 499.00,
                'offer_price' => 149.00,
                'badge' => 'SAVE 70%',
                'description' => 'ক্যানভা প্রো ১ বছরের জন্য আনলিমিটেড অ্যাক্সেস। ১০০ মিলিয়নের বেশি প্রিমিয়াম ফটো, গ্রাফিক্স, ভিডিও, ফন্ট এবং এআই টুলস ব্যবহার করতে পারবেন।',
                'features' => [
                    '১ বছরের ফুল প্রিমিয়াম সাবস্ক্রিপশন',
                    'নিজের ব্যক্তিগত মেইলে ইনভাইটেশন লিংক',
                    'ম্যাজিক এআই স্টুডিও ও ব্যাকগ্রাউন্ড রিমুভার',
                    '১০০+ মিলিয়ন প্রিমিয়াম স্টক ফটো ও টেমপ্লেট',
                ],
                'is_active' => false, // Out of stock
            ],
            [
                'slug' => 'duolingo-super-12m',
                'name' => 'Duolingo Super (ডুওলিঙ্গো সুপার ১২ মাস)',
                'subtitle' => 'Unlimited Hearts, Practice Hub & No Ads Experience',
                'regular_price' => 799.00,
                'offer_price' => 399.00,
                'badge' => 'SAVE 50%',
                'description' => 'ডুওলিঙ্গো সুপার সাবস্ক্রিপশন ১ বছরের জন্য। কোনো বিজ্ঞাপন ছাড়া আনলিমিটেড হার্টস নিয়ে যেকোনো ভাষা দ্রুত শিখুন।',
                'features' => [
                    '১২ মাসের সুপার সাবস্ক্রিপশন',
                    'আনলিমিটেড হার্টস (Unlimited Hearts)',
                    'কোনো বিজ্ঞাপন (Ad-Free) থাকবে না',
                    'পার্সোনাল অ্যাকাউন্টে সরাসরি যুক্ত হবে',
                ],
                'is_active' => false, // Out of stock
            ],
            [
                'slug' => 'chatgpt-plus-team',
                'name' => 'ChatGPT Plus / OpenAI Team (চ্যাটজিপিটি প্লাস)',
                'subtitle' => 'GPT-4o, DALL-E 3, Canvas & Deep Research Access',
                'regular_price' => 1200.00,
                'offer_price' => 599.00,
                'badge' => 'SAVE 50%',
                'description' => 'ওপেনএআই চ্যাটজিপিটি প্লাস / টিম অ্যাকাউন্ট। GPT-4o মডেলের হাই স্পিড রেসপন্স, ডাল-ই ৩ ইমেজ জেনারেশন এবং ভয়েস মোড অ্যাক্সেস।',
                'features' => [
                    'GPT-4o ও GPT-4o-mini আনলিমিটেড অ্যাক্সেস',
                    'ডাল-ই ৩ দিয়ে হাই রেজোলিউশন ছবি তৈরি',
                    'ওয়েব ব্রাউজিং ও কোড ইন্টারপ্রেটার',
                    'কাস্টম GPTs তৈরি ও ব্যবহারের সুবিধা',
                ],
                'is_active' => false, // Out of stock
            ],
            [
                'slug' => 'capcut-pro-pc-mobile',
                'name' => 'CapCut Pro (ক্যাপকাট প্রো পিসি ও মোবাইল)',
                'subtitle' => 'All Pro Effects, Auto Captions & 4K 60FPS Export',
                'regular_price' => 650.00,
                'offer_price' => 249.00,
                'badge' => 'SAVE 60%',
                'description' => 'ক্যাপকাট প্রো ভিডিও এডিটর। অটো ক্যাপশন, প্রিমিয়াম ফিল্টার, ব্যাকগ্রাউন্ড রিমুভাল এবং ৪কে ভিডিও এক্সপোর্টের সম্পূর্ণ সুবিধা।',
                'features' => [
                    'পিসি এবং মোবাইল উভয় ডিভাইসে চলবে',
                    'প্রিমিয়াম ভিডিও এফেক্ট ও ট্রানজিশন আনলক',
                    'অটোমেটিক সাবটাইটেল / ক্যাপশন জেনারেটর',
                    'ওয়াটারমার্ক ছাড়া ফুল ৪কে এক্সপোর্ট',
                ],
                'is_active' => false, // Out of stock
            ],
            [
                'slug' => 'bangla-landing-page-bundle',
                'name' => '250+ Bangla Landing Page Template Bundle',
                'subtitle' => 'Elementor & HTML High-Converting E-commerce Templates',
                'regular_price' => 499.00,
                'offer_price' => 199.00,
                'badge' => 'SAVE 60%',
                'description' => 'বাংলাদেশে প্রোডাক্ট বিক্রির জন্য ২৫০টির বেশি হাই-কনভার্টিং ল্যান্ডিং পেজ টেমপ্লেট। এলিমেন্টর ও এইচটিএমএল রেডি-টু-ইউজ বান্ডেল।',
                'features' => [
                    '২৫০+ রেডিমেড বাংলা ল্যান্ডিং পেজ',
                    'এলিমেন্টর ড্র্যাগ অ্যান্ড ড্রপ এডিটেবল',
                    'বিকাশ ও নগদ পেমেন্ট সেকশন রেডি',
                    'ফুল মোবাইল ফ্রেন্ডলি ও ফাস্ট লোডিং',
                ],
                'is_active' => false, // Out of stock
            ],
            [
                'slug' => 'office-365-5tb',
                'name' => 'Microsoft 365 + 5TB OneDrive (লাইফটাইম)',
                'subtitle' => 'Word, Excel, PowerPoint, Outlook & 5TB Cloud Storage',
                'regular_price' => 999.00,
                'offer_price' => 349.00,
                'badge' => 'SAVE 65%',
                'description' => 'মাইক্রোসফট অফিস ৩৬৫ প্রিমিয়াম অ্যাপস প্যাকেজ। সাথে পাচ্ছেন ৫টিবি ওয়ানড্রাইভ ক্লাউড স্টোরেজ এবং ৫টি ডিভাইসে ব্যবহারের সুবিধা।',
                'features' => [
                    '৫টি ডিভাইসে (PC, Mac, Mobile) একসাথে চালানো যাবে',
                    'লেটেস্ট Word, Excel, PowerPoint, Outlook',
                    '৫ টেরাবাইট (5TB) ক্লাউড ব্যাকআপ স্টোরেজ',
                    'অফিসিয়াল মাইক্রোসফট লাইসেন্স অ্যাকাউন্ট',
                ],
                'is_active' => false, // Out of stock
            ],
            [
                'slug' => 'claude-ai-pro',
                'name' => 'Claude AI Pro / Cloud AI (ক্লড এআই প্রো)',
                'subtitle' => 'Claude 3.5 Sonnet, Artifacts & 5x Higher Usage',
                'regular_price' => 1100.00,
                'offer_price' => 549.00,
                'badge' => 'SAVE 50%',
                'description' => 'অ্যানথ্রপিক ক্লড এআই প্রো (Claude AI Pro / Cloud AI) সাবস্ক্রিপশন। কোডিং, রিসার্চ ও কনটেন্ট রাইটিংয়ের জন্য Claude 3.5 Sonnet মডেল অ্যাক্সেস ও আর্টিফ্যাক্টস সুবিধা।',
                'features' => [
                    'Claude 3.5 Sonnet মডেলের ফুল অ্যাক্সেস',
                    '৫ গুণ বেশি প্রশ্ন ও কোডিং লিমিট',
                    'ইন্টারেক্টিভ আর্টিফ্যাক্টস (Artifacts) সাপোর্ট',
                    'প্রজেক্ট নলেজবেস ও ফাইল অ্যানালাইসিস',
                ],
                'is_active' => false, // Out of stock
            ],
        ];

        foreach ($products as $pData) {
            Product::updateOrCreate(['slug' => $pData['slug']], $pData);
        }

        // 4. Ensure demo available links exist only on initial fresh setup
        $gemini = Product::where('slug', 'gemini-pro-18m')->first();
        $hasSeededLinks = Setting::where('key', 'initial_sample_links_seeded')->exists();
        if ($gemini && !$hasSeededLinks && DigitalLink::where('product_id', $gemini->id)->count() == 0) {
            $sampleLinks = [
                'https://serviceactivation.google.com/subscription/new/ACQpIIhV73g7sq4r60Ojy53xwiiZCB80-GEMINI-FRESH1-Yk9Hv1KRkAch9VRn4Z6t2zOKZiaFXW8dWdY5E5co41KOOoAkdwvnVjAXrxTP8HlsiP7ugAgO6Eh7fe',
                'https://serviceactivation.google.com/subscription/new/ACQpIIhV73g7sq4r60Ojy53xwiiZCB80-GEMINI-FRESH2-Yk9Hv1KRkAch9VRn4Z6t2zOKZiaFXW8dWdY5E5co41KOOoAkdwvnVjAXrxTP8HlsiP7ugAgO6Eh7fe',
                'https://serviceactivation.google.com/subscription/new/ACQpIIhV73g7sq4r60Ojy53xwiiZCB80-GEMINI-FRESH3-Yk9Hv1KRkAch9VRn4Z6t2zOKZiaFXW8dWdY5E5co41KOOoAkdwvnVjAXrxTP8HlsiP7ugAgO6Eh7fe',
                'https://serviceactivation.google.com/subscription/new/ACQpIIhV73g7sq4r60Ojy53xwiiZCB80-GEMINI-FRESH4-Yk9Hv1KRkAch9VRn4Z6t2zOKZiaFXW8dWdY5E5co41KOOoAkdwvnVjAXrxTP8HlsiP7ugAgO6Eh7fe',
                'https://serviceactivation.google.com/subscription/new/ACQpIIhV73g7sq4r60Ojy53xwiiZCB80-GEMINI-FRESH5-Yk9Hv1KRkAch9VRn4Z6t2zOKZiaFXW8dWdY5E5co41KOOoAkdwvnVjAXrxTP8HlsiP7ugAgO6Eh7fe',
            ];
            foreach ($sampleLinks as $link) {
                DigitalLink::firstOrCreate(
                    ['link_url' => $link],
                    ['product_id' => $gemini->id, 'status' => 'available']
                );
            }
            Setting::create(['key' => 'initial_sample_links_seeded', 'value' => '1']);
        }
    }
}
