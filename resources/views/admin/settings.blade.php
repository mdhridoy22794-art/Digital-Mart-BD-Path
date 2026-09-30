@extends('admin.layout')

@section('page_title', 'ওয়েবসাইট ও মেটা পিক্সেল সেটিংস')
@section('page_subtitle', 'ফেসবুক মেটা পিক্সেল, পেমেন্ট নম্বর ও স্টোর কনফিগারেশন')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto pb-12">

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- 1. Meta Pixel & Tracking Settings -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center gap-3.5 pb-5 mb-6 border-b border-slate-100">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-brands fa-meta text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 font-bn">মেটা পিক্সেল ও মার্কেটিং ট্র্যাকিং (Meta Pixel Integration)</h3>
                    <p class="text-xs text-slate-400 font-bn">ফেসবুক বিজ্ঞাপন ট্র্যাক এবং আরও বেশি সেলস জেনারেট করতে এখানে পিক্সেল আইডি বসান</p>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 flex items-center justify-between font-bn">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-crosshairs text-blue-500"></i>
                            <span>Meta Pixel ID (ফেসবুক পিক্সেল আইডি)</span>
                        </span>
                        <span class="text-[11px] text-brand-600 font-mono font-medium">উদাঃ 123456789012345</span>
                    </label>

                    <div class="relative">
                        <input type="text" name="meta_pixel_id" value="{{ $settings['meta_pixel_id'] ?? '' }}" 
                               placeholder="আপনার ফেসবুক মেটা পিক্সেল আইডিটি এখানে পেস্ট করুন"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200/90 text-xs sm:text-sm font-mono text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
                    </div>

                    <!-- Pixel Automation Features Indicator -->
                    <div class="mt-3 p-3.5 rounded-2xl bg-blue-50/60 border border-blue-100 flex flex-wrap items-center gap-2 text-[11px] text-blue-900 font-bn">
                        <span class="font-bold flex items-center gap-1 text-blue-700">
                            <i class="fa-solid fa-circle-check text-blue-600"></i>
                            স্বয়ংক্রিয় ট্র্যাককৃত ইভেন্টসমূহ:
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-white border border-blue-200 text-blue-700 font-mono font-semibold">PageView</span>
                        <span class="px-2 py-0.5 rounded-md bg-white border border-blue-200 text-blue-700 font-mono font-semibold">InitiateCheckout</span>
                        <span class="px-2 py-0.5 rounded-md bg-white border border-blue-200 text-blue-700 font-mono font-semibold">Purchase (With Value & Currency BDT)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 font-bn flex items-center justify-between">
                        <span>কাস্টম স্ক্রিপ্ট কোড (Custom Header Scripts - ঐচ্ছিক)</span>
                        <span class="text-[11px] text-slate-400 font-normal">Google Tag Manager, TikTok Pixel, Google Analytics</span>
                    </label>
                    <textarea name="custom_header_script" rows="3" 
                              placeholder="<!-- Google Analytics, TikTok Pixel ইত্যাদি থাকলে এখানে পেস্ট করতে পারেন -->"
                              class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200/90 text-xs font-mono text-slate-800 placeholder-slate-400 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition">{{ $settings['custom_header_script'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- 2. Payment Gateway Numbers -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 relative overflow-hidden">
            <div class="flex items-center gap-3.5 pb-5 mb-6 border-b border-slate-100">
                <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-wallet text-xl"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 font-bn">ম্যানুয়াল বিকাশ ও নগদ পেমেন্ট নম্বর</h3>
                    <p class="text-xs text-slate-400 font-bn">চেকআউট পেজে কাস্টমাররা ম্যানুয়ালি Send Money করতে চাইলে এই নম্বরে টাকা পাঠাবে</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- bKash Box -->
                <div class="p-5 rounded-2xl bg-gradient-to-br from-pink-500/5 to-transparent border border-pink-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-extrabold text-xs text-pink-700 font-bn flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-pink-600"></span>
                            bKash পার্সোনাল নম্বর
                        </span>
                        <span class="px-2 py-0.5 rounded bg-pink-100 text-pink-700 font-mono text-[10px] font-bold">Send Money</span>
                    </div>
                    <input type="text" name="bkash_number" value="{{ $settings['bkash_number'] ?? '01934779775' }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-white border border-pink-200 text-sm font-mono font-bold text-slate-800 focus:border-pink-600 focus:ring-2 focus:ring-pink-500/20 outline-none transition">
                </div>

                <!-- Nagad Box -->
                <div class="p-5 rounded-2xl bg-gradient-to-br from-orange-500/5 to-transparent border border-orange-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-extrabold text-xs text-orange-700 font-bn flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-orange-600"></span>
                            নগদ পার্সোনাল নম্বর
                        </span>
                        <span class="px-2 py-0.5 rounded bg-orange-100 text-orange-700 font-mono text-[10px] font-bold">Send Money</span>
                    </div>
                    <input type="text" name="nagad_number" value="{{ $settings['nagad_number'] ?? '01934779775' }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-white border border-orange-200 text-sm font-mono font-bold text-slate-800 focus:border-orange-600 focus:ring-2 focus:ring-orange-500/20 outline-none transition">
                </div>
            </div>
        </div>

        <!-- 3. ZiniPay Automated Payment Gateway Settings -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center gap-3.5 pb-5 mb-6 border-b border-slate-100">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-brand-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-bolt-lightning text-xl text-purple-600"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 font-bn">ZiniPay অটোমেটেড পেমেন্ট গেটওয়ে (Auto Payment Gateway)</h3>
                    <p class="text-xs text-slate-400 font-bn">অটোমেটিক বিকাশ, নগদ, রকেট পেমেন্ট ও স্বয়ংক্রিয় ১-সেকেন্ড লিংক ডেলিভারি</p>
                </div>
            </div>

            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 font-bn">গেটওয়ে স্ট্যাটাস (Status)</label>
                        <select name="zinipay_status" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/90 text-xs sm:text-sm font-bn text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                            <option value="active" {{ ($settings['zinipay_status'] ?? 'active') == 'active' ? 'selected' : '' }}>সক্রিয় (Active - Recommended)</option>
                            <option value="inactive" {{ ($settings['zinipay_status'] ?? '') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2 font-bn flex items-center justify-between">
                            <span>ZiniPay Brand Key / API Key *</span>
                            <span class="text-[11px] text-brand-600 font-normal">dash.zinipay.com থেকে প্রাপ্ত</span>
                        </label>
                        <input type="text" name="zinipay_api_key" value="{{ $settings['zinipay_api_key'] ?? '4e16b90fb1c397d0b5a4c4f32deab4349dad06172d2683fa' }}" required
                               placeholder="আপনার ZiniPay Brand Key এখানে দিন"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/90 text-xs sm:text-sm font-mono text-slate-800 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/10 outline-none transition">
                    </div>
                </div>

                <!-- Webhook URL Info Box -->
                <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-100 space-y-2 text-xs">
                    <span class="font-bold text-purple-900 block font-bn">
                        <i class="fa-solid fa-link text-brand-purple mr-1"></i>
                        ZiniPay Webhook URL (যদি ব্র্যান্ড সেটিংসে দিতে চান):
                    </span>
                    <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-purple-200 font-mono text-[11px] text-slate-700 select-all">
                        <span>https://digital-mart-bd.onrender.com/payment/zinipay/webhook</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Contact & Social -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 relative overflow-hidden">
            <div class="flex items-center gap-3.5 pb-5 mb-6 border-b border-slate-100">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-brands fa-whatsapp text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 font-bn">কাস্টমার সাপোর্ট ও ফেসবুক পেজ</h3>
                    <p class="text-xs text-slate-400 font-bn">ওয়েবসাইটের হেডার, ফুটার এবং ভাসমান হোয়াটসঅ্যাপ হেল্পলাইনে প্রদর্শিত হবে</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 font-bn flex items-center gap-1.5">
                        <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                        <span>হোয়াটসঅ্যাপ হেল্পলাইন নম্বর</span>
                    </label>
                    <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '+8801934779775' }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/90 text-xs sm:text-sm font-mono text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 font-bn flex items-center gap-1.5">
                        <i class="fa-brands fa-facebook text-blue-600"></i>
                        <span>অফিসিয়াল ফেসবুক পেজ লিংক</span>
                    </label>
                    <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? 'https://www.facebook.com/digitalmartbd.store' }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/90 text-xs sm:text-sm font-mono text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                </div>
            </div>
        </div>

        <!-- 4. Announcement & Pricing -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 relative overflow-hidden">
            <div class="flex items-center gap-3.5 pb-5 mb-6 border-b border-slate-100">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-brand-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-tags text-xl"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 font-bn">স্টোর অ্যানাউন্সমেন্ট ও মূল প্রোডাক্ট প্রাইসিং</h3>
                    <p class="text-xs text-slate-400 font-bn">ওয়েবসাইটের টপ নোটিশ বার ও জেমিনাই প্রো-এর মূল্য নিয়ন্ত্রণ</p>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 font-bn flex items-center gap-1.5">
                        <i class="fa-solid fa-bullhorn text-amber-500"></i>
                        <span>টপ অ্যানাউন্সমেন্ট বার টেক্সট</span>
                    </label>
                    <input type="text" name="announcement_text" value="{{ $settings['announcement_text'] ?? '⚡ মেগা অফার: মাত্র ২০০ টাকায় ১৮ মাসের গুগল জেমিনাই এআই প্রো! অফারটি সীমিত সময়ের জন্য।' }}" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/90 text-xs sm:text-sm text-slate-800 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition font-bn">
                </div>

                @if($product)
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-800 font-bn flex items-center gap-1.5">
                            <i class="fa-solid fa-sparkles text-brand-600"></i>
                            <span>{{ $product->name }} (মূল প্রোডাক্ট)</span>
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold font-bn">
                            ইন স্টক
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5 font-bn">রেগুলার মূল্য (৳ Regular Price)</label>
                            <input type="number" step="0.01" id="regPriceInput" name="regular_price" value="{{ $product->regular_price }}" required
                                   oninput="calculateDiscount()"
                                   class="w-full px-4 py-2 rounded-xl bg-white border border-slate-200 font-mono font-bold text-sm text-slate-800 focus:border-brand-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5 font-bn">অফার মূল্য (৳ Offer Price)</label>
                            <input type="number" step="0.01" id="offerPriceInput" name="offer_price" value="{{ $product->offer_price }}" required
                                   oninput="calculateDiscount()"
                                   class="w-full px-4 py-2 rounded-xl bg-white border border-slate-200 font-mono font-bold text-sm text-brand-600 focus:border-brand-500 outline-none transition">
                        </div>
                    </div>

                    <div id="discountPreviewBadge" class="text-xs font-bold text-purple-700 font-bn flex items-center gap-1.5 pt-1">
                        <i class="fa-solid fa-badge-percent"></i>
                        <span id="discountText">ক্যালকুলেট করা হচ্ছে...</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sticky Save Button Bar -->
        <div class="sticky bottom-6 z-20 bg-white/90 backdrop-blur-md p-4 rounded-3xl border border-slate-200/90 shadow-xl flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-bn">
                <i class="fa-solid fa-shield-check text-emerald-500 text-sm"></i>
                <span>পরিবর্তন করার পর সেভ বাটনে চাপ দিন</span>
            </div>

            <button type="submit" 
                    class="px-8 py-3 rounded-2xl gradient-brand text-white font-bold text-xs shadow-lg shadow-purple-600/30 hover:opacity-95 transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span class="font-bn text-sm">সকল সেটিংস সংরক্ষণ করুন (Save Settings)</span>
            </button>
        </div>

    </form>

    <!-- 5. Change Password Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 relative overflow-hidden mt-8">
        <div class="flex items-center gap-3.5 pb-5 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-lock text-xl"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-base text-slate-900 font-bn">অ্যাডমিন পাসওয়ার্ড পরিবর্তন (Change Password)</h3>
                <p class="text-xs text-slate-400 font-bn">আপনার অ্যাডমিন অ্যাকাউন্টের পাসওয়ার্ড পরিবর্তন করে নতুন পাসওয়ার্ড দিন</p>
            </div>
        </div>

        <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">বর্তমান পাসওয়ার্ড (Current)</label>
                    <input type="password" name="current_password" required placeholder="বর্তমান পাসওয়ার্ড লিখুন"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-rose-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">নতুন পাসওয়ার্ড (New)</label>
                    <input type="password" name="password" required minlength="6" placeholder="নতুন পাসওয়ার্ড দিন"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-rose-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">কনফার্ম নতুন পাসওয়ার্ড</label>
                    <input type="password" name="password_confirmation" required minlength="6" placeholder="পুনরায় নতুন পাসওয়ার্ড লিখুন"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-rose-500 outline-none transition">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition flex items-center gap-2 font-bn">
                    <i class="fa-solid fa-key text-xs"></i>
                    <span>পাসওয়ার্ড আপডেট করুন</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function calculateDiscount() {
        const reg = parseFloat(document.getElementById('regPriceInput')?.value) || 0;
        const off = parseFloat(document.getElementById('offerPriceInput')?.value) || 0;
        const discountText = document.getElementById('discountText');
        if (!discountText) return;

        if (reg > 0 && off > 0 && reg > off) {
            const pct = Math.round(((reg - off) / reg) * 100);
            discountText.innerText = `কাস্টমাররা দেখতে পাবে: ${pct}% ছাড় (৳${reg - off} টাকা সেভ)`;
        } else {
            discountText.innerText = 'রেগুলার ও অফার প্রাইস যাচাই করুন';
        }
    }
    calculateDiscount();
</script>
@endpush
