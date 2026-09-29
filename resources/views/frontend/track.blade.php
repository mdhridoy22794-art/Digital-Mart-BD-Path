@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 md:py-14">

    <!-- Header Section -->
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-purple-100 text-brand-purple flex items-center justify-center text-2xl mx-auto mb-3 shadow-sm">
            <i class="fa-solid fa-magnifying-glass-location"></i>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight font-bn">অর্ডার ও ডেলিভারি ট্র্যাক করুন</h1>
        <p class="text-xs md:text-sm text-slate-500 mt-1 font-bn">আপনার ব্যবহৃত মোবাইল নম্বর বা ট্রানজেকশন আইডি (TrxID) দিয়ে সার্চ করুন</p>
    </div>

    <!-- Search Form -->
    <div class="glass-card p-6 rounded-2xl border border-slate-200 shadow-md mb-8">
        <form action="{{ route('order.track') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-search"></i>
                </div>
                <input type="text" name="query" value="{{ $query }}" required
                       placeholder="মোবাইল নম্বর (উদাঃ 017xxxxxxxx) বা TrxID লিখুন" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 text-sm focus:border-brand-purple focus:ring-1 focus:ring-brand-purple outline-none font-bn">
            </div>
            <button type="submit" class="py-3 px-6 rounded-xl text-white font-bold text-sm gradient-brand gradient-brand-hover shadow-md shadow-purple-500/20 flex items-center justify-center gap-2 transition shrink-0 font-bn">
                <span>খুঁজুন (Search)</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>
    </div>

    <!-- Search Results -->
    @if($query)
        @if($orders->count() > 0)
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-slate-700 font-bn">খুঁজে পাওয়া অর্ডারসমূহ ({{ $orders->count() }}টি):</h2>

            @foreach($orders as $order)
            @php
                $deliveredList = array_values(array_filter(preg_split('/\r\n|\r|\n/', (string) $order->delivered_link)));
                $totalQty = $order->quantity ?: count($deliveredList) ?: 1;
            @endphp
            <div class="glass-card p-5 md:p-6 rounded-3xl border border-purple-100 shadow-sm space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-xs text-slate-400 font-bn">অর্ডার নম্বর:</span>
                        <span class="font-bold font-en text-slate-900 text-sm">#{{ $order->order_number }}</span>
                    </div>
                    <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-3 py-1 rounded-full font-bn flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>ডেলিভারি সম্পন্ন (Completed)</span>
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block font-bn">কাস্টমার নাম:</span>
                        <span class="font-semibold text-slate-800 font-bn">{{ $order->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-bn">মোবাইল নম্বর:</span>
                        <span class="font-semibold text-slate-800 font-en">{{ $order->customer_phone }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-bn">অর্ডার পরিমাণ:</span>
                        <span class="font-bold text-brand-purple font-en text-sm">{{ $totalQty }}টি লিংক</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-bn">পরিশোধিত মূল্য:</span>
                        <span class="font-bold text-slate-900 font-en text-sm">৳{{ number_format($order->amount, 0) }}</span>
                    </div>
                </div>

                <!-- Delivered Links Container -->
                @if(count($deliveredList) > 0)
                <div class="p-4 rounded-2xl bg-slate-900 text-white space-y-3 mt-2 border border-slate-800">
                    <div class="flex items-center justify-between text-xs text-emerald-400 font-semibold">
                        <span class="font-bn flex items-center gap-1.5">
                            <i class="fa-solid fa-gift text-brand-purple"></i>
                            <span>আপনার ডেলিভারিকৃত লিংকসমূহ (সিরিয়াল অনুযায়ী):</span>
                        </span>
                        
                        @if(count($deliveredList) > 1)
                        <button type="button" 
                                onclick="navigator.clipboard.writeText(`{{ implode('\n', $deliveredList) }}`).then(() => alert('সবগুলো লিংক ক্লিপবোর্ডে কপি করা হয়েছে!'));"
                                class="px-2.5 py-1 rounded-lg bg-brand-purple hover:bg-purple-700 text-white text-[11px] font-bold font-bn transition shadow-sm">
                            <i class="fa-regular fa-copy"></i>
                            <span>সব লিংক একসাথে কপি</span>
                        </button>
                        @endif
                    </div>

                    <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                        @foreach($deliveredList as $idx => $linkUrl)
                        <div class="p-3 rounded-xl bg-slate-950 border border-slate-800/80 space-y-1.5 text-left">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-mono text-purple-300 font-bold">#{{ $idx + 1 }}</span>
                                <a href="{{ $linkUrl }}" target="_blank" class="text-purple-400 hover:text-white flex items-center gap-1 font-bn">
                                    <span>ব্রাউজারে খুলুন</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>
                            <div class="font-mono text-xs text-purple-200 break-all select-all py-1">
                                {{ $linkUrl }}
                            </div>
                            <button type="button" 
                                    onclick="navigator.clipboard.writeText('{{ $linkUrl }}').then(() => alert('লিংক #{{ $idx + 1 }} কপি করা হয়েছে!'));"
                                    class="w-full py-1.5 px-3 rounded-lg bg-purple-900/40 hover:bg-brand-purple text-purple-200 hover:text-white text-[11px] font-semibold flex items-center justify-center gap-1.5 transition">
                                <i class="fa-regular fa-copy"></i>
                                <span>এই লিংকটি কপি করুন</span>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 glass-card rounded-2xl border border-slate-200">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                <i class="fa-solid fa-file-circle-question"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800 font-bn">কোনো অর্ডার পাওয়া যায়নি!</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto font-bn">
                "{{ $query }}" নম্বর বা TrxID দিয়ে কোনো অর্ডার রেকর্ড নেই। অনুগ্রহ করে নম্বরটি সঠিকভাবে দেখে পুনরায় চেষ্টা করুন।
            </p>
        </div>
        @endif
    @endif

</div>
@endsection
