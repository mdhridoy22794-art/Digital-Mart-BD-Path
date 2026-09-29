@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-2.5 sm:px-4 py-2 sm:py-5 space-y-4 sm:space-y-8">

    <!-- ================= 1. RESPONSIVE HERO BANNER ================= -->
    <div class="relative w-full rounded-xl sm:rounded-3xl overflow-hidden shadow-lg sm:shadow-xl border border-purple-500/20 bg-slate-950 group">
        <a href="{{ route('product.details', 'gemini-pro-18m') }}" class="block relative w-full overflow-hidden cursor-pointer">
            <img src="{{ asset('images/banner_hero.jpg') }}" 
                 alt="Digital Mart BD - All Digital Solutions" 
                 class="w-full h-auto max-h-[360px] sm:max-h-[460px] object-cover sm:object-contain md:object-cover mx-auto transform group-hover:scale-[1.01] transition-transform duration-500">
            <!-- Subtle gradient vignette -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>
        </a>

        <!-- Trust Badges Bar (Directly below the banner) -->
        <div class="bg-slate-900 border-t border-white/10 px-3 sm:px-6 py-2.5 sm:py-3.5">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-center text-[10px] sm:text-xs text-slate-300">
                <div class="flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-certificate text-purple-400 text-xs sm:text-sm"></i>
                    <span class="font-bold">Trusted Service</span>
                </div>
                <div class="flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-bolt text-yellow-400 text-xs sm:text-sm"></i>
                    <span class="font-bold">Instant 1-Sec Delivery</span>
                </div>
                <div class="flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-emerald-400 text-xs sm:text-sm"></i>
                    <span class="font-bold">100% Safe & Secure</span>
                </div>
                <div class="flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-headset text-pink-400 text-xs sm:text-sm"></i>
                    <span class="font-bold">24/7 Dedicated Support</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= 2. CATEGORY ICONS / CARDS ================= -->
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 sm:gap-4">
        <!-- 1. AI Tools -->
        <a href="{{ route('product.details', 'gemini-pro-18m') }}" class="scale-hover p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-purple-200 shadow-sm flex flex-col items-center text-center group btn-press">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-purple-50 text-brand-purple flex items-center justify-center text-lg sm:text-2xl mb-1.5 sm:mb-2 group-hover:bg-brand-purple group-hover:text-white transition duration-300 shadow-inner">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <span class="font-bold text-[11px] sm:text-xs text-slate-800 group-hover:text-brand-purple transition">AI Tools</span>
        </a>

        <!-- 2. Software -->
        <a href="#trending-section" class="scale-hover p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col items-center text-center group btn-press">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg sm:text-2xl mb-1.5 sm:mb-2 group-hover:bg-blue-600 group-hover:text-white transition duration-300 shadow-inner">
                <i class="fa-solid fa-laptop-code"></i>
            </div>
            <span class="font-bold text-[11px] sm:text-xs text-slate-800 group-hover:text-blue-600 transition">Software</span>
        </a>

        <!-- 3. Templates -->
        <a href="#trending-section" class="scale-hover p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col items-center text-center group btn-press">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg sm:text-2xl mb-1.5 sm:mb-2 group-hover:bg-emerald-600 group-hover:text-white transition duration-300 shadow-inner">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <span class="font-bold text-[11px] sm:text-xs text-slate-800 group-hover:text-emerald-600 transition">Templates</span>
        </a>

        <!-- 4. All Products -->
        <a href="#trending-section" class="scale-hover p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col items-center text-center group btn-press">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg sm:text-2xl mb-1.5 sm:mb-2 group-hover:bg-pink-600 group-hover:text-white transition duration-300 shadow-inner">
                <i class="fa-solid fa-store"></i>
            </div>
            <span class="font-bold text-[11px] sm:text-xs text-slate-800 group-hover:text-pink-600 transition">All Products</span>
        </a>

        <!-- 5. Canva & Design -->
        <a href="{{ route('product.details', 'canva-pro-1-year') }}" class="scale-hover p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col items-center text-center group btn-press">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg sm:text-2xl mb-1.5 sm:mb-2 group-hover:bg-indigo-600 group-hover:text-white transition duration-300 shadow-inner">
                <i class="fa-solid fa-palette"></i>
            </div>
            <span class="font-bold text-[11px] sm:text-xs text-slate-800 group-hover:text-indigo-600 transition">Canva Pro</span>
        </a>

        <!-- 6. Social Media -->
        <a href="#recent-section" class="scale-hover p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col items-center text-center group btn-press">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg sm:text-2xl mb-1.5 sm:mb-2 group-hover:bg-orange-600 group-hover:text-white transition duration-300 shadow-inner">
                <i class="fa-solid fa-thumbs-up"></i>
            </div>
            <span class="font-bold text-[11px] sm:text-xs text-slate-800 group-hover:text-orange-600 transition">Social Media</span>
        </a>
    </div>

    <!-- ================= 3. TRENDING PRODUCTS ================= -->
    <div id="trending-section" class="space-y-3 sm:space-y-5 pt-1">
        <!-- Section Header -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-2.5 sm:pb-3">
            <div>
                <h2 class="text-base sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-1.5 sm:gap-2">
                    <span class="w-2 sm:w-2.5 h-5 sm:h-6 bg-gradient-to-b from-purple-600 to-pink-500 rounded-full inline-block"></span>
                    <span>TRENDING PRODUCTS</span>
                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5">সবচেয়ে জনপ্রিয় এবং সর্বাধিক বিক্রিত ডিজিটাল সার্ভিসসমূহ</p>
            </div>
            <a href="#trending-section" class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full bg-purple-50 text-brand-purple text-[10px] sm:text-xs font-bold hover:bg-brand-purple hover:text-white transition flex items-center gap-1 btn-press">
                <span>MORE</span>
                <i class="fa-solid fa-chevron-right text-[8px] sm:text-[10px]"></i>
            </a>
        </div>

        <!-- Trending Grid (Strictly 2 Columns on Mobile, 4 on Desktop) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-4 md:gap-5">
            @foreach($trendingProducts as $index => $prod)
            <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between overflow-hidden group scale-hover">
                
                <div>
                    <!-- Product Square Image Box -->
                    <div class="relative aspect-square overflow-hidden bg-slate-900 p-1 sm:p-2">
                        <!-- Top Left Badge -->
                        <span class="absolute top-1.5 left-1.5 sm:top-3 sm:left-3 z-10 bg-gradient-to-r from-purple-600 to-pink-500 text-white text-[8px] sm:text-[10px] font-black px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded uppercase tracking-wider shadow">
                            {{ $prod->badge }}
                        </span>

                        <!-- Top Right Wishlist Heart -->
                        <button class="absolute top-1.5 right-1.5 sm:top-3 sm:right-3 z-10 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white/80 hover:bg-white text-slate-600 hover:text-pink-600 flex items-center justify-center transition shadow-sm" title="Add to Wishlist">
                            <i class="fa-regular fa-heart text-[10px] sm:text-xs"></i>
                        </button>

                        <!-- Product Cover Image -->
                        <a href="{{ route('product.details', $prod->slug) }}" class="block w-full h-full overflow-hidden rounded-lg sm:rounded-xl">
                            <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>

                        <!-- In Stock / Out of Stock Banner -->
                        @if($prod->is_active)
                        <div class="absolute bottom-1.5 left-1.5 sm:bottom-3 sm:left-3 z-10 bg-emerald-600 text-white text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded shadow flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                            <span>ইন স্টক</span>
                        </div>
                        @else
                        <div class="absolute bottom-1.5 left-1.5 sm:bottom-3 sm:left-3 z-10 bg-rose-600 text-white text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded shadow">
                            স্টক শেষ
                        </div>
                        @endif
                    </div>

                    <!-- Details Area -->
                    <div class="p-2.5 sm:p-4 space-y-1 sm:space-y-2">
                        <a href="{{ route('product.details', $prod->slug) }}" class="block font-bold text-xs sm:text-sm text-slate-900 group-hover:text-brand-purple transition line-clamp-2 min-h-[32px] sm:min-h-[40px] leading-tight sm:leading-snug" title="{{ $prod->name }}">
                            {{ $prod->name }}
                        </a>

                        <!-- Rating Stars -->
                        <div class="flex items-center gap-0.5 sm:gap-1 text-amber-400 text-[9px] sm:text-xs">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="text-slate-400 text-[9px] sm:text-[11px] font-en ml-0.5 sm:ml-1">(5.0)</span>
                        </div>

                        <!-- Price Tag -->
                        <div class="flex items-baseline gap-1.5 sm:gap-2 pt-0.5">
                            <span class="text-sm sm:text-lg font-black font-en text-purple-700 sm:text-slate-900">৳{{ number_format($prod->offer_price, 0) }}</span>
                            <span class="text-[10px] sm:text-xs font-en text-slate-400 line-through">৳{{ number_format($prod->regular_price, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Action Buttons -->
                <div class="p-2.5 sm:p-4 pt-0">
                    <a href="{{ route('product.details', $prod->slug) }}" 
                       class="w-full py-1.5 sm:py-2.5 px-2 sm:px-4 rounded-lg sm:rounded-xl text-center text-[10px] sm:text-xs font-bold transition-all duration-200 flex items-center justify-center gap-1.5 gradient-brand gradient-brand-hover text-white shadow-sm shadow-purple-500/20 btn-press btn-shine">
                        <i class="fa-solid fa-bag-shopping text-[10px] sm:text-xs"></i>
                        <span>অর্ডার করুন</span>
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </div>

    <!-- ================= 4. RECENT PRODUCTS ================= -->
    <div id="recent-section" class="space-y-3 sm:space-y-5 pt-2 sm:pt-4">
        <!-- Section Header -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-2.5 sm:pb-3">
            <div>
                <h2 class="text-base sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-1.5 sm:gap-2">
                    <span class="w-2 sm:w-2.5 h-5 sm:h-6 bg-gradient-to-b from-pink-500 to-purple-600 rounded-full inline-block"></span>
                    <span>RECENT PRODUCTS</span>
                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5">স্টোরে সদ্য যুক্ত হওয়া নতুন ডিজিটাল প্যাকেজসমূহ</p>
            </div>
            <a href="#recent-section" class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full bg-purple-50 text-brand-purple text-[10px] sm:text-xs font-bold hover:bg-brand-purple hover:text-white transition flex items-center gap-1 btn-press">
                <span>MORE</span>
                <i class="fa-solid fa-chevron-right text-[8px] sm:text-[10px]"></i>
            </a>
        </div>

        <!-- Recent Grid (Strictly 2 Columns on Mobile, 4 on Desktop) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-4 md:gap-5">
            @foreach($recentProducts as $prod)
            <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between overflow-hidden group scale-hover">
                
                <div>
                    <!-- Product Image Box -->
                    <div class="relative aspect-square overflow-hidden bg-slate-900 p-1 sm:p-2">
                        <span class="absolute top-1.5 left-1.5 sm:top-3 sm:left-3 z-10 bg-gradient-to-r from-purple-600 to-pink-500 text-white text-[8px] sm:text-[10px] font-black px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded uppercase tracking-wider shadow">
                            {{ $prod->badge }}
                        </span>

                        <button class="absolute top-1.5 right-1.5 sm:top-3 sm:right-3 z-10 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white/80 hover:bg-white text-slate-600 hover:text-pink-600 flex items-center justify-center transition shadow-sm" title="Add to Wishlist">
                            <i class="fa-regular fa-heart text-[10px] sm:text-xs"></i>
                        </button>

                        <a href="{{ route('product.details', $prod->slug) }}" class="block w-full h-full overflow-hidden rounded-lg sm:rounded-xl">
                            <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>

                        @if($prod->is_active)
                        <div class="absolute bottom-1.5 left-1.5 sm:bottom-3 sm:left-3 z-10 bg-emerald-600 text-white text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded shadow flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                            <span>ইন স্টক</span>
                        </div>
                        @else
                        <div class="absolute bottom-1.5 left-1.5 sm:bottom-3 sm:left-3 z-10 bg-rose-600 text-white text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded shadow">
                            স্টক শেষ
                        </div>
                        @endif
                    </div>

                    <!-- Details Area -->
                    <div class="p-2.5 sm:p-4 space-y-1 sm:space-y-2">
                        <a href="{{ route('product.details', $prod->slug) }}" class="block font-bold text-xs sm:text-sm text-slate-900 group-hover:text-brand-purple transition line-clamp-2 min-h-[32px] sm:min-h-[40px] leading-tight sm:leading-snug" title="{{ $prod->name }}">
                            {{ $prod->name }}
                        </a>

                        <div class="flex items-center gap-0.5 sm:gap-1 text-amber-400 text-[9px] sm:text-xs">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="text-slate-400 text-[9px] sm:text-[11px] font-en ml-0.5 sm:ml-1">(5.0)</span>
                        </div>

                        <div class="flex items-baseline gap-1.5 sm:gap-2 pt-0.5">
                            <span class="text-sm sm:text-lg font-black font-en text-purple-700 sm:text-slate-900">৳{{ number_format($prod->offer_price, 0) }}</span>
                            <span class="text-[10px] sm:text-xs font-en text-slate-400 line-through">৳{{ number_format($prod->regular_price, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Action Buttons -->
                <div class="p-2.5 sm:p-4 pt-0">
                    <a href="{{ route('product.details', $prod->slug) }}" 
                       class="w-full py-1.5 sm:py-2.5 px-2 sm:px-4 rounded-lg sm:rounded-xl text-center text-[10px] sm:text-xs font-bold transition-all duration-200 flex items-center justify-center gap-1.5 gradient-brand gradient-brand-hover text-white shadow-sm shadow-purple-500/20 btn-press btn-shine">
                        <i class="fa-solid fa-bag-shopping text-[10px] sm:text-xs"></i>
                        <span>অর্ডার করুন</span>
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('scripts')
<style>
    /* Button press ripple & animation */
    .btn-press:active {
        transform: scale(0.95);
    }
</style>
@endsection
