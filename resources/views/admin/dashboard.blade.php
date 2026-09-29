@extends('admin.layout')

@section('page_title', 'ড্যাশবোর্ড ওভারভিউ')
@section('page_subtitle', 'ডিজিটাল প্রোডাক্ট বিক্রয় ও লাইভ ডেলিভারি পরিসংখ্যান')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- 1. Hero Welcome & Quick Action Banner -->
    <div class="relative overflow-hidden rounded-3xl gradient-dark-card border border-slate-800 p-6 sm:p-8 text-white shadow-xl">
        <!-- Background Ambient Glow -->
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-brand-600/20 blur-3xl pointer-events-none"></div>
        <div class="absolute right-40 -bottom-20 w-60 h-60 rounded-full bg-pink-600/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-500/20 border border-brand-500/30 text-purple-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>স্বয়ংক্রিয় লাইভ ডেলিভারি ইঞ্জিন সক্রিয়</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-bn text-white tracking-tight">
                    স্বাগতম, অ্যাডমিনিস্ট্রেটর! 👋
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 font-bn max-w-xl leading-relaxed">
                    আপনার ডিজিটাল মার্ট বিডি স্টোরের অর্ডার ও লিংক পুল এখান থেকেই রিয়েল-টাইমে নিয়ন্ত্রণ করতে পারবেন। প্রতিটি অর্ডারের সাথে সাথেই কাস্টমার তার ইউনিক লিংক পেয়ে যায়।
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('admin.gemini') }}" 
                   class="px-5 py-3 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-extrabold text-xs shadow-lg shadow-amber-500/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-sparkles"></i>
                    <span class="font-bn">জেমিনাই অফার কন্ট্রোল</span>
                </a>
                <a href="{{ route('admin.links') }}" 
                   class="px-5 py-3 rounded-2xl bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span class="font-bn">বাল্ক লিংক আপলোড</span>
                </a>
                <a href="{{ route('admin.settings') }}" 
                   class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white border border-white/15 font-semibold text-xs transition backdrop-blur-sm flex items-center gap-2">
                    <i class="fa-solid fa-sliders"></i>
                    <span class="font-bn">পিক্সেল ও সেটিংস</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Metric Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Revenue -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider font-bn">মোট বিক্রয় (Revenue)</span>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-brand-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-extrabold font-mono text-slate-900 tracking-tight">
                    ৳{{ number_format($totalRevenue, 0) }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold text-emerald-600 font-bn">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    <span>সম্পূর্ণ সফল ক্যাশ-ইন</span>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider font-bn">মোট সফল অর্ডার</span>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="fa-solid fa-cart-check"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-extrabold font-mono text-slate-900 tracking-tight">
                    {{ $totalOrders }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold text-blue-600 font-bn">
                    <i class="fa-solid fa-bolt"></i>
                    <span>অটো ভেরিফাইড ও ডেলিভার্ড</span>
                </div>
            </div>
        </div>

        <!-- Available Stock Links -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider font-bn">স্টকে থাকা লিংক (Unsold)</span>
                <div class="w-12 h-12 rounded-2xl {{ $availableLinks > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="fa-solid fa-link"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-extrabold font-mono tracking-tight {{ $availableLinks > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $availableLinks }}টি
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold {{ $availableLinks > 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bn">
                    @if($availableLinks > 0)
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>স্টক রেডি - অর্ডার চলতেছে</span>
                    @else
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>স্টক শেষ! এখনই লিংক দিন</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sold Links -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider font-bn">ডেলিভারিকৃত লিংক (Sold)</span>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
            </div>
            <div>
                <div class="text-3xl font-extrabold font-mono text-slate-900 tracking-tight">
                    {{ $soldLinks }}টি
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-xs font-semibold text-slate-500 font-bn">
                    <i class="fa-solid fa-lock"></i>
                    <span>সিঙ্গেল ইউজ - আর ব্যবহার হবে না</span>
                </div>
            </div>
        </div>

    </div>

    <!-- 3. Stock Capacity Bar & Alert -->
    @php
        $totalPool = $availableLinks + $soldLinks;
        $stockPercent = $totalPool > 0 ? round(($availableLinks / $totalPool) * 100) : 0;
    @endphp
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
            <div>
                <h3 class="font-extrabold text-sm text-slate-800 font-bn flex items-center gap-2">
                    <i class="fa-solid fa-battery-half text-brand-600"></i>
                    <span>লিংক পুল ক্যাপাসিটি ও হেলথ স্ট্যাটাস</span>
                </h3>
                <p class="text-xs text-slate-500 font-bn">মোট স্টকের মধ্যে কত শতাংশ এখনো বিক্রির জন্য প্রস্তুত আছে</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold font-mono px-3 py-1 rounded-full {{ $availableLinks > 2 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200 animate-pulse' }}">
                    {{ $availableLinks }} / {{ $totalPool }} লিংক বাকি ({{ $stockPercent }}%)
                </span>
                <a href="{{ route('admin.links') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 font-bn flex items-center gap-1">
                    <span>পুল ম্যানেজ করুন</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden p-0.5 border border-slate-200">
            <div class="h-full rounded-full transition-all duration-700 {{ $availableLinks > 2 ? 'bg-gradient-to-r from-brand-600 to-emerald-500' : 'bg-gradient-to-r from-rose-500 to-amber-500' }}"
                 style="width: {{ max($stockPercent, 4) }}%"></div>
        </div>
    </div>

    <!-- 4. Low Stock Alert (Conditional) -->
    @if($availableLinks <= 2)
    <div class="p-5 rounded-3xl bg-gradient-to-r from-amber-500/10 via-rose-500/10 to-transparent border border-rose-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-rose-500/30">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
            </div>
            <div>
                <span class="font-extrabold text-sm text-slate-900 block font-bn">জরুরি স্টক সতর্কতা (Low Stock Alert)!</span>
                <span class="text-xs text-slate-600 font-bn">আপনার স্টকে মাত্র {{ $availableLinks }}টি লিংক আছে। কাস্টমারদের নিরবচ্ছিন্ন সেবা দিতে এখনই নতুন জেমিনাই প্রো লিংক কিউতে যুক্ত করুন।</span>
            </div>
        </div>
        <a href="{{ route('admin.links') }}" 
           class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition shrink-0 font-bn text-center">
            <i class="fa-solid fa-plus mr-1"></i>
            নতুন লিংক আপলোড করুন
        </a>
    </div>
    @endif

    <!-- 5. Recent Orders Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-brand-600 flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-base"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn">সাম্প্রতিক সফল অর্ডারসমূহ (Recent Orders)</h3>
                    <p class="text-xs text-slate-400 font-bn">সর্বশেষ সফলভাবে সম্পন্ন হওয়া ১০টি কাস্টমার অর্ডার</p>
                </div>
            </div>

            <a href="{{ route('admin.orders') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-50 hover:bg-purple-50 text-slate-700 hover:text-brand-600 border border-slate-200 hover:border-purple-200 text-xs font-bold transition font-bn">
                <span>সকল অর্ডার তালিকা দেখুন</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6 font-bn">অর্ডার নং</th>
                        <th class="py-4 px-6 font-bn">কাস্টমার তথ্য</th>
                        <th class="py-4 px-6 font-bn">পেমেন্ট মেথড</th>
                        <th class="py-4 px-6 font-bn">TrxID (ট্রানজেকশন)</th>
                        <th class="py-4 px-6 font-bn">টাকা</th>
                        <th class="py-4 px-6 font-bn">ডেলিভারিকৃত লিংক</th>
                        <th class="py-4 px-6 font-bn">তারিখ ও সময়</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50/80 transition duration-150">
                        <!-- Order Number -->
                        <td class="py-4 px-6">
                            <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                #{{ $order->order_number }}
                            </span>
                        </td>

                        <!-- Customer Details -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full gradient-brand text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                    {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block font-bn">{{ $order->customer_name }}</span>
                                    <span class="text-slate-400 font-mono text-[11px] flex items-center gap-1">
                                        <i class="fa-solid fa-phone text-[9px]"></i>
                                        {{ $order->customer_phone }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Payment Method -->
                        <td class="py-4 px-6">
                            @if(strtolower($order->payment_method) === 'bkash')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-pink-50 text-pink-700 border border-pink-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                                <span>bKash</span>
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                <span>Nagad</span>
                            </span>
                            @endif
                        </td>

                        <!-- TrxID with 1-click Copy -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <span class="font-mono font-bold text-slate-800 bg-slate-100/70 px-2 py-1 rounded border border-slate-200 text-[11px]">
                                    {{ $order->trx_id }}
                                </span>
                                <button onclick="copyText('{{ $order->trx_id }}', this)" 
                                        class="p-1 text-slate-400 hover:text-brand-600 rounded hover:bg-slate-100 transition" 
                                        title="Copy TrxID">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                            </div>
                        </td>

                        <!-- Amount -->
                        <td class="py-4 px-6">
                            <span class="font-mono font-extrabold text-slate-900 text-sm">
                                ৳{{ number_format($order->amount, 0) }}
                            </span>
                        </td>

                        <!-- Delivered Link with Copy -->
                        <td class="py-4 px-6 max-w-xs">
                            <div class="flex items-center gap-2">
                                <a href="{{ $order->delivered_link }}" target="_blank" 
                                   class="font-mono text-[11px] text-brand-600 hover:underline truncate max-w-[160px] block" 
                                   title="{{ $order->delivered_link }}">
                                    {{ $order->delivered_link }}
                                </a>
                                <button onclick="copyText('{{ $order->delivered_link }}', this)" 
                                        class="px-2 py-0.5 rounded bg-purple-50 text-brand-600 hover:bg-brand-600 hover:text-white border border-purple-200 text-[10px] font-bold font-mono transition shrink-0">
                                    Copy
                                </button>
                            </div>
                        </td>

                        <!-- Date Time -->
                        <td class="py-4 px-6 text-slate-400 font-mono text-[11px] whitespace-nowrap">
                            {{ $order->created_at->format('d M, Y') }}<br>
                            <span class="text-slate-500 font-semibold">{{ $order->created_at->format('h:i A') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 px-6 text-center text-slate-400 font-bn">
                            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                            <span class="text-sm font-semibold block text-slate-600">এখনও কোনো অর্ডার পাওয়া যায়নি</span>
                            <span class="text-xs text-slate-400">কাস্টমাররা অর্ডার করলেই তা সাথে সাথে এখানে প্রদর্শিত হবে।</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
