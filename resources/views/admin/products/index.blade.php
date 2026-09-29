@extends('admin.layout')

@section('page_title', 'সকল প্রোডাক্ট ও সাবস্ক্রিপশন ক্যাটালগ')
@section('page_subtitle', 'স্টোরের সকল ডিজিটাল প্রোডাক্ট এডিট, ডিলিট, স্টক কন্ট্রোল ও নতুন প্রোডাক্ট যুক্ত করুন')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Header Actions Banner -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-base text-slate-900 font-bn">স্টোর প্রোডাক্ট ইনভেন্টরি ({{ $products->count() }}টি প্রোডাক্ট)</h3>
                <p class="text-xs text-slate-400 font-bn">যেকোনো প্রোডাক্টের টাইটেল, দাম, ছবি, ফিচার বা স্টক এডিট করতে 'এডিট' বাটনে চাপ দিন।</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.create') }}" 
               class="px-5 py-3 rounded-2xl gradient-brand text-white font-bold text-xs shadow-lg shadow-purple-600/30 hover:opacity-95 transition flex items-center gap-2 font-bn">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>নতুন প্রোডাক্ট যোগ করুন</span>
            </a>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6 font-bn">পোস্টার ও নাম</th>
                        <th class="py-4 px-6 font-bn">মূল্য (Price)</th>
                        <th class="py-4 px-6 font-bn">ব্যাজ</th>
                        <th class="py-4 px-6 font-bn">স্টকের লিংক</th>
                        <th class="py-4 px-6 font-bn">স্ট্যাটাস</th>
                        <th class="py-4 px-6 text-center font-bn">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($products as $prod)
                    <tr class="hover:bg-slate-50/80 transition duration-150">
                        
                        <!-- Product Info & Poster -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-xl bg-slate-900 overflow-hidden shrink-0 border border-slate-200 shadow-sm">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="max-w-xs">
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" class="font-bold text-slate-900 hover:text-brand-purple transition font-bn block line-clamp-1">
                                        {{ $prod->name }}
                                    </a>
                                    <span class="text-[11px] text-slate-400 font-bn line-clamp-1">{{ $prod->subtitle ?? 'কোনো সাবটাইটেল নেই' }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">/product/{{ $prod->slug }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Pricing -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="font-mono">
                                <span class="font-extrabold text-slate-900 text-sm">৳{{ number_format($prod->offer_price, 0) }}</span>
                                <span class="text-xs text-slate-400 line-through ml-1">৳{{ number_format($prod->regular_price, 0) }}</span>
                            </div>
                        </td>

                        <!-- Badge -->
                        <td class="py-4 px-6">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-50 text-brand-purple border border-purple-200">
                                {{ $prod->badge ?? 'OFFER' }}
                            </span>
                        </td>

                        <!-- Links Stock -->
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold {{ $prod->available_links_count > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                {{ $prod->available_links_count }}টি লিংক বাকি
                            </span>
                        </td>

                        <!-- Active / Out of stock Toggle -->
                        <td class="py-4 px-6">
                            <form action="{{ route('admin.products.toggle', $prod->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold transition shadow-sm {{ $prod->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}"
                                        title="স্ট্যাটাস পরিবর্তন করতে ক্লিক করুন">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $prod->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                                    <span class="font-bn">{{ $prod->is_active ? 'ইন স্টক (In Stock)' : 'স্টক শেষ (Out of Stock)' }}</span>
                                </button>
                            </form>
                        </td>

                        <!-- Action Buttons -->
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- View Live -->
                                <a href="{{ route('product.details', $prod->slug) }}" target="_blank" 
                                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition flex items-center justify-center shadow-sm" 
                                   title="লাইভ পেজ দেখুন">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('admin.products.edit', $prod->id) }}" 
                                   class="w-8 h-8 rounded-xl bg-purple-50 hover:bg-brand-600 text-brand-purple hover:text-white border border-purple-200 hover:border-brand-600 transition flex items-center justify-center shadow-sm" 
                                   title="এডিট করুন">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" 
                                      onsubmit="return confirm('আপনি কি নিশ্চিত \'{{ $prod->name }}\' প্রোডাক্টটি এবং এর সমস্ত লিংক সম্পূর্ণ মুছে ফেলতে চান?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-500 hover:text-white border border-rose-200/60 hover:border-rose-600 transition flex items-center justify-center shadow-sm" 
                                            title="মুছে ফেলুন">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 px-6 text-center text-slate-400 font-bn">
                            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <span class="text-sm font-semibold block text-slate-600">কোনো প্রোডাক্ট পাওয়া যায়নি</span>
                            <span class="text-xs text-slate-400">উপরে 'নতুন প্রোডাক্ট যোগ করুন' বাটনে ক্লিক করে প্রথম প্রোডাক্ট তৈরি করুন।</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
