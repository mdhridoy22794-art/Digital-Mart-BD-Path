@extends('frontend.layout')

@section('title', 'অর্ডার সফল হয়েছে - ' . $order->order_number . ' | Digital Mart BD')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 sm:py-12">
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden">
        
        <!-- Header Banner -->
        <div class="gradient-brand p-6 sm:p-8 text-white text-center relative overflow-hidden">
            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg border border-white/30 animate-bounce">
                <i class="fa-solid fa-circle-check text-emerald-300"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-bn">পেমেন্ট সফল ও লিংক ডেলিভারি!</h1>
            <p class="text-xs sm:text-sm text-purple-100 mt-1.5 font-bn">
                আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে। নিচে আপনার কাঙ্ক্ষিত সাবস্ক্রিপশন লিংক প্রদান করা হলো।
            </p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">

            <!-- Order Summary Card -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                <div class="p-2">
                    <span class="text-[11px] text-slate-500 block font-bn">অর্ডার নম্বর</span>
                    <strong class="text-xs sm:text-sm font-mono font-bold text-slate-800">{{ $order->order_number }}</strong>
                </div>
                <div class="p-2">
                    <span class="text-[11px] text-slate-500 block font-bn">মোট পরিমাণ</span>
                    <strong class="text-xs sm:text-sm font-mono font-bold text-purple-700">{{ $order->quantity ?? 1 }}টি লিংক</strong>
                </div>
                <div class="p-2">
                    <span class="text-[11px] text-slate-500 block font-bn">পরিশোধিত অর্থ</span>
                    <strong class="text-xs sm:text-sm font-mono font-bold text-emerald-600">৳{{ number_format($order->amount, 0) }}</strong>
                </div>
                <div class="p-2">
                    <span class="text-[11px] text-slate-500 block font-bn">পেমেন্ট মেথড</span>
                    <strong class="text-xs sm:text-sm font-mono font-bold uppercase text-slate-800">{{ $order->payment_method }}</strong>
                </div>
            </div>

            @if(!empty($order->trx_id))
            <div class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-purple-50/60 border border-purple-100 text-xs">
                <span class="text-slate-600 font-bn">ট্রানজেকশন আইডি (TrxID):</span>
                <span class="font-mono font-bold text-brand-purple">{{ $order->trx_id }}</span>
            </div>
            @endif

            <!-- Links Delivery Section -->
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-1 border-b border-slate-100">
                    <span class="text-sm font-extrabold text-slate-900 font-bn flex items-center gap-2">
                        <i class="fa-solid fa-gift text-brand-purple"></i>
                        <span>আপনার অ্যাক্টিভেশন লিংকসমূহ (ক্রমানুসারে):</span>
                    </span>
                    
                    @if(count($deliveredLinks) > 0)
                    <button type="button" onclick="copyAllLinks()" id="copyAllSuccessBtn"
                            class="px-3.5 py-1.5 rounded-xl gradient-brand text-white font-bold text-xs hover:opacity-95 transition flex items-center gap-1.5 shadow-sm">
                        <i class="fa-regular fa-copy"></i>
                        <span id="copyAllText">সব লিংক একসাথে কপি</span>
                    </button>
                    @endif
                </div>

                @if(count($deliveredLinks) > 0)
                    <div class="space-y-3">
                        @foreach($deliveredLinks as $item)
                        <div class="p-4 rounded-2xl bg-slate-900 text-left border border-slate-800 shadow-md space-y-2.5">
                            <div class="flex items-center justify-between text-xs text-emerald-400 font-semibold">
                                <span class="flex items-center gap-1.5 font-bn">
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono text-[11px] font-bold">#{{ $item['serial'] }}</span>
                                    <span>অ্যাক্টিভেশন লিংক #{{ $item['serial'] }}:</span>
                                </span>
                                <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" 
                                   class="text-[11px] text-purple-300 hover:text-white flex items-center gap-1 transition">
                                    <span>ওপেন করুন</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-950 font-mono text-xs text-purple-200 select-all break-all border border-slate-800/80">
                                {{ $item['url'] }}
                            </div>

                            <button type="button" onclick="copyText('{{ addslashes($item['url']) }}', this)" 
                                    class="w-full py-2.5 px-4 rounded-xl bg-purple-600/30 hover:bg-brand-purple text-purple-200 hover:text-white border border-purple-500/30 text-xs font-semibold flex items-center justify-center gap-2 transition">
                                <i class="fa-regular fa-copy"></i>
                                <span>এই লিংকটি কপি করুন</span>
                            </button>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bn">
                        <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                        লিংক প্রসেস হতে সামান্য কয়েক সেকেন্ড দেরি হতে পারে। পৃষ্ঠাটি রিফ্রেশ দিন অথবা হেল্পলাইনে যোগাযোগ করুন।
                    </div>
                @endif
            </div>

            <!-- Activation Instructions Guide -->
            <div class="p-5 rounded-2xl bg-purple-50/70 border border-purple-200 text-xs text-slate-700 space-y-2">
                <h4 class="font-bold text-brand-purple flex items-center gap-2 text-sm font-bn">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>সার্ভিসটি কীভাবে চালু করবেন?</span>
                </h4>
                <ol class="list-decimal list-inside space-y-1.5 text-slate-600 text-xs font-bn leading-relaxed">
                    <li>উপরের লিংকের পাশের <strong>'কপি'</strong> বাটনে চাপ দিন (অথবা 'সব লিংক একসাথে কপি' করুন)।</li>
                    <li>আপনার ফোনের বা কম্পিউটারের <strong>Google Chrome</strong> ব্রাউজারে গিয়ে লিংকটি পেস্ট করে এন্টার দিন।</li>
                    <li>গুগল ওয়ান (Google One) পেজ লোড হলে নিচে <strong>"Activate plan"</strong> বাটনে ট্যাপ করুন।</li>
                    <li>ব্যাস! সাথে সাথেই আপনার জিমেইল অ্যাকাউন্টে জেমিনাই প্রো ও ৫টিবি স্টোরেজ সক্রিয় হয়ে যাবে।</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <a href="{{ route('order.track', ['query' => $order->order_number]) }}" 
                   class="w-full py-3 px-4 rounded-xl border border-purple-300 text-brand-purple hover:bg-purple-50 font-bold text-xs text-center flex items-center justify-center gap-2 transition font-bn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>অর্ডার ট্র্যাক / হিস্টোরি দেখুন</span>
                </a>

                <a href="{{ route('home') }}" 
                   class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs text-center flex items-center justify-center gap-2 transition font-bn">
                    <i class="fa-solid fa-house"></i>
                    <span>হোম পেজে ফিরে যান</span>
                </a>
            </div>

        </div>

    </div>
</div>

<textarea id="allLinksStorage" class="hidden">{{ $order->delivered_link }}</textarea>

@endsection

@section('scripts')
<script>
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-check text-emerald-400"></i><span class="text-emerald-400">কপি সম্পন্ন হয়েছে!</span>`;
            setTimeout(() => {
                btn.innerHTML = originalHtml;
            }, 2500);
        });
    }

    function copyAllLinks() {
        const text = document.getElementById('allLinksStorage')?.value || '';
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => {
            const btnText = document.getElementById('copyAllText');
            btnText.innerText = 'সব লিংক কপি হয়েছে!';
            setTimeout(() => {
                btnText.innerText = 'সব লিংক একসাথে কপি';
            }, 2500);
        });
    }

    // Fire Facebook Pixel Purchase if loaded
    @if(isset($settings['meta_pixel_id']) && !empty($settings['meta_pixel_id']))
    if (typeof fbq === 'function') {
        fbq('track', 'Purchase', {
            content_name: "{{ $order->product->name ?? 'Digital Subscription' }}",
            value: {{ (float) $order->amount }},
            currency: 'BDT',
            order_id: "{{ $order->order_number }}"
        });
    }
    @endif
</script>
@endsection
