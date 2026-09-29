@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-2.5 sm:px-4 py-3 sm:py-8 space-y-4 sm:space-y-10">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-brand-purple transition">হোম</a>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
        <span>ডিজিটাল প্রোডাক্ট</span>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
        <span class="text-brand-purple font-semibold truncate">{{ $product->name }}</span>
    </div>

    <!-- Main Product Showcase Grid -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm p-4 sm:p-8 md:p-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            
            <!-- Left Col: Product Visual Image Card -->
            <div class="md:col-span-5 space-y-4">
                <div class="relative rounded-2xl overflow-hidden border border-purple-500/20 shadow-xl bg-slate-900 group">
                    <!-- Discount Badge -->
                    <span class="absolute top-4 left-4 z-10 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-[11px] font-black px-3 py-1 rounded-full uppercase tracking-wider shadow">
                        {{ $product->badge }}
                    </span>

                    <!-- Stock Tag -->
                    @if($product->is_active && $stockCount > 0)
                    <span class="absolute top-4 right-4 z-10 bg-emerald-500/90 text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1.5 backdrop-blur-sm shadow">
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                        <span>{{ $stockCount }} In Stock</span>
                    </span>
                    @else
                    <span class="absolute top-4 right-4 z-10 bg-rose-500/90 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                        Out of Stock
                    </span>
                    @endif

                    <!-- Big Real Product Image -->
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                         class="w-full aspect-square object-cover transform group-hover:scale-105 transition-transform duration-500">
                </div>

                <!-- Trust Guarantees -->
                <div class="grid grid-cols-2 gap-2 text-center text-xs text-slate-600">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-yellow-500 text-sm"></i>
                        <span class="font-semibold">স্বয়ংক্রিয় লিংক ডেলিভারি</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-shield-check text-emerald-500 text-sm"></i>
                        <span class="font-semibold">১০০% ওনার গ্যারান্টি</span>
                    </div>
                </div>
            </div>

            <!-- Right Col: Product Information & Purchase -->
            <div class="md:col-span-7 space-y-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-snug">
                        {{ $product->name }}
                    </h1>
                    <p class="text-xs text-slate-500 font-en mt-1">{{ $product->subtitle }}</p>

                    <!-- Rating & Reviews -->
                    <div class="flex items-center gap-3 mt-3">
                        <div class="flex items-center text-amber-400 text-sm">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <span class="text-xs text-slate-500 font-en font-semibold">5.0 (৮৯ জন কাস্টমার রিভিউ)</span>
                        <span class="text-slate-300">•</span>
                        <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>ভেরিফাইড ডিজিটাল প্রোডাক্ট</span>
                        </span>
                    </div>
                </div>

                <!-- Price Box -->
                <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-100 flex items-baseline gap-3">
                    <span class="text-3xl sm:text-4xl font-black font-en text-slate-900">৳{{ number_format($product->offer_price, 0) }}</span>
                    <span class="text-base font-en text-slate-400 line-through">৳{{ number_format($product->regular_price, 0) }}</span>
                    <span class="ml-auto bg-gradient-to-r from-purple-600 to-pink-500 text-white text-xs font-bold px-3 py-1 rounded-md shadow-sm">
                        {{ $product->badge }}
                    </span>
                </div>

                <!-- Live Stock Counter Badge -->
                <div>
                    @if($product->is_active && $stockCount > 0)
                    <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-xl">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>বর্তমানে স্টকে আছে: <strong>{{ $stockCount }}টি লিংক অবশিষ্ট</strong> (পেমেন্ট করলেই ১ সেকেন্ডে সিরিয়াল অনুযায়ী ডেলিভারি)</span>
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-2 rounded-xl">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500 text-base"></i>
                        <span>দুঃখিত! এই প্রোডাক্টটির স্টক বর্তমানে শেষ হয়ে গেছে। খুব শীঘ্রই নতুন স্টক যোগ হবে।</span>
                    </div>
                    @endif
                </div>

                <!-- Features Checklist -->
                <div class="space-y-2.5 pt-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">প্যাকেজের প্রধান সুবিধাসমূহ:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700">
                        @foreach($product->features as $feat)
                        <div class="flex items-start gap-2">
                            <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </span>
                            <span>{{ $feat }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Purchase Buttons with Quantity Stepper -->
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    @if($product->is_active && $stockCount > 0)
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        
                        <!-- Interactive Quantity Stepper (+ and - buttons) -->
                        <div class="flex items-center justify-between border-2 border-purple-200 bg-purple-50/50 rounded-2xl p-1.5 w-full sm:w-44 text-xs font-bold shrink-0">
                            <button type="button" onclick="changeQuantity(-1)" 
                                    class="w-9 h-9 rounded-xl bg-white border border-purple-200 hover:bg-purple-600 hover:text-white text-slate-700 flex items-center justify-center text-sm font-bold transition shadow-sm active:scale-95"
                                    title="পরিমাণ কমান">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <div class="text-center px-2 select-none">
                                <span id="displayQty" class="font-en text-lg font-black text-slate-900 block leading-tight">1</span>
                                <span class="text-[10px] text-slate-400 block font-bn">টি লিংক</span>
                            </div>
                            <button type="button" onclick="changeQuantity(1)" 
                                    class="w-9 h-9 rounded-xl bg-white border border-purple-200 hover:bg-purple-600 hover:text-white text-slate-700 flex items-center justify-center text-sm font-bold transition shadow-sm active:scale-95"
                                    title="পরিমাণ বাড়ান">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                        <!-- Buy Now Button with Dynamic Total Price -->
                        <button onclick="openCheckoutModal()" 
                                class="w-full py-3.5 px-6 rounded-2xl text-white font-bold text-base gradient-brand gradient-brand-hover shadow-xl shadow-purple-500/25 flex items-center justify-center gap-2 transition duration-300 transform hover:scale-102 btn-shine">
                            <i class="fa-solid fa-bolt text-yellow-300"></i>
                            <span id="buyBtnText">এখনই অর্ডার করুন (Buy Now) - ৳{{ number_format($product->offer_price, 0) }}</span>
                        </button>
                    </div>
                    @else
                    <div class="space-y-2">
                        <button disabled class="w-full py-3.5 px-6 rounded-xl bg-slate-200 text-slate-400 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban"></i>
                            <span>স্টক শেষ (Out of Stock)</span>
                        </button>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '8801934779775') }}?text=Hello,%20I%20want%20to%20know%20when%20{{ urlencode($product->name) }}%20will%20be%20in%20stock." 
                           target="_blank" class="w-full py-2.5 px-4 rounded-xl border border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold flex items-center justify-center gap-2 transition">
                            <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                            <span>হোয়াটসঅ্যাপে স্টক আসার নোটিফিকেশন রিকোয়েস্ট করুন</span>
                        </a>
                    </div>
                    @endif

                    <p class="text-center text-[11px] text-slate-400 flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-lock text-emerald-500"></i>
                        <span>বিকাশ ও নগদ পেমেন্ট সফল হওয়ামাত্র স্বয়ংক্রিয়ভাবে স্ক্রিনে সিরিয়াল অনুযায়ী লিংকগুলো চলে আসবে</span>
                    </p>
                </div>

            </div>

        </div>
    </div>

    <!-- Product Description Section (if description is present) -->
    @if(!empty($product->description))
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-4 shadow-sm">
        <h2 class="text-xl font-black text-slate-900 flex items-center gap-2 font-bn">
            <span class="w-2 h-5 bg-brand-purple rounded-full inline-block"></span>
            <span>প্রোডাক্টের বিস্তারিত বিবরণ (Package Details)</span>
        </h2>
        <div class="prose max-w-none text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-bn">
            {{ $product->description }}
        </div>
    </div>
    @endif

    <!-- How to Activate (3-Step Visual Guide) -->
    <div id="how-to-buy" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6 shadow-sm">
        <div class="text-center max-w-xl mx-auto">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">৩টি সহজ ধাপে সার্ভিসটি অ্যাক্টিভ করুন</h2>
            <p class="text-xs text-slate-500 mt-1">পেমেন্ট কনফার্ম হওয়া মাত্র স্ক্রিনেই আপনার প্রতিটি ইউনিক লিংক সিরিয়াল অনুযায়ী প্রদর্শিত হবে</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="p-5 rounded-2xl bg-purple-50/50 border border-purple-100 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-brand-purple text-white flex items-center justify-center text-lg font-bold font-en mb-3 shadow-md shadow-purple-500/20">
                    ১
                </div>
                <h3 class="font-bold text-sm text-slate-800 mb-1">অর্ডার ও পেমেন্ট</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    পরিমাণ নির্বাচন করে 'Buy Now' চাপুন এবং প্রদর্শিত বিকাশ বা নগদ নম্বরে মোট টাকা Send Money করে TrxID দিন।
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-pink-50/50 border border-pink-100 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-brand-pink text-white flex items-center justify-center text-lg font-bold font-en mb-3 shadow-md shadow-pink-500/20">
                    ২
                </div>
                <h3 class="font-bold text-sm text-slate-800 mb-1">ইনস্ট্যান্ট লিংক কপি</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    ১ সেকেন্ডে স্ক্রিনে সিরিয়াল অনুযায়ী (#১, #২, #৩...) জেনুইন অ্যাক্টিভেশন লিংক চলে আসবে। আলাদা বা একসাথে কপি করুন।
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-emerald-50/50 border border-emerald-100 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-lg font-bold font-en mb-3 shadow-md shadow-emerald-500/20">
                    ৩
                </div>
                <h3 class="font-bold text-sm text-slate-800 mb-1">গুগলে অ্যাক্টিভেশন</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    ফোনের Chrome ব্রাউজারে লিংকটি পেস্ট করে Google One পেজে "Activate plan" এ ট্যাপ করলেই ১৮ মাসের জন্য সফলভাবে সক্রিয়!
                </p>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="space-y-6 pt-4">
        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <span class="w-2 h-5 bg-brand-purple rounded-full inline-block"></span>
            <span>অন্যান্য ডিজিটাল সার্ভিসসমূহ (Related Products)</span>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($relatedProducts as $rel)
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm hover:shadow-lg transition flex flex-col justify-between">
                <div>
                    <div class="h-28 rounded-xl bg-slate-900 text-white flex items-center justify-center mb-3">
                        <span class="text-xs font-bold font-en text-purple-300">{{ $rel->name }}</span>
                    </div>
                    <h4 class="font-bold text-xs text-slate-800 line-clamp-1">{{ $rel->name }}</h4>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="font-bold font-en text-slate-900 text-sm">৳{{ number_format($rel->offer_price, 0) }}</span>
                        <span class="font-en text-slate-400 line-through text-xs">৳{{ number_format($rel->regular_price, 0) }}</span>
                    </div>
                </div>
                <a href="{{ route('product.details', $rel->slug) }}" class="mt-3 w-full py-1.5 rounded-lg border border-purple-200 text-brand-purple hover:bg-purple-50 text-xs font-semibold text-center block transition">
                    বিস্তারিত দেখুন
                </a>
            </div>
            @endforeach
        </div>
    </div>

</div>

<!-- ================= CHECKOUT & DELIVERY MODAL ================= -->
<div id="checkoutModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-slate-950/75 backdrop-blur-md overflow-y-auto">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden my-4 sm:my-6 transform transition-all max-h-[92vh] flex flex-col">

        <!-- Modal Header -->
        <div class="gradient-brand p-4 sm:p-5 text-white flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-bolt text-yellow-300 text-xl"></i>
                <div>
                    <h3 class="font-bold text-base leading-tight">{{ $product->name }}</h3>
                    <p class="text-xs text-purple-200" id="modalSubtitle">অর্ডার সম্পন্ন করুন - প্রতি লিংক ৳{{ number_format($product->offer_price, 0) }}</p>
                </div>
            </div>
            <button onclick="closeCheckoutModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <div class="overflow-y-auto p-4 sm:p-6 flex-grow">
            <!-- STAGE 1: ORDER & PAYMENT FORM -->
            <div id="checkoutFormSection" class="space-y-4">
                
                <!-- Error Alert -->
                <div id="orderErrorBox" class="hidden p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation shrink-0 text-sm"></i>
                    <span id="orderErrorMessage"></span>
                </div>

                <form id="orderForm" onsubmit="submitOrder(event)">
                    @csrf
                    <input type="hidden" name="product_slug" value="{{ $product->slug }}">
                    <input type="hidden" name="quantity" id="hiddenFormQty" value="1">

                    <!-- Quantity Control inside Modal -->
                    <div class="p-3.5 rounded-2xl bg-purple-50/70 border border-purple-200 flex items-center justify-between mb-4">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block font-bn">অর্ডার পরিমাণ (Quantity):</span>
                            <span class="text-[11px] text-slate-500 font-bn">ইউনিট মূল্য ৳{{ number_format($product->offer_price, 0) }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-2 py-1 rounded-xl border border-purple-200 shadow-sm">
                            <button type="button" onclick="changeQuantity(-1)" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-purple-600 hover:text-white text-slate-700 flex items-center justify-center text-xs font-bold transition">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span id="modalQtyDisplay" class="w-8 text-center font-en text-sm font-black text-slate-900 select-none">1</span>
                            <button type="button" onclick="changeQuantity(1)" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-purple-600 hover:text-white text-slate-700 flex items-center justify-center text-xs font-bold transition">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">আপনার নাম *</label>
                            <input type="text" name="customer_name" required placeholder="উদাঃ মোঃ রহিম" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-brand-purple outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">মোবাইল নম্বর *</label>
                            <input type="tel" name="customer_phone" required placeholder="017xxxxxxxx" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-brand-purple outline-none">
                        </div>
                    </div>

                    <!-- Payment Method Toggle -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">পেমেন্ট মেথড নির্বাচন করুন *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-3 flex items-center gap-2 hover:border-pink-500 transition has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50/40">
                                <input type="radio" name="payment_method" value="bkash" checked onchange="updatePaymentInstructions('bkash')" class="accent-pink-600">
                                <span class="font-bold text-xs text-pink-600 font-en">bKash (বিকাশ)</span>
                            </label>

                            <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-3 flex items-center gap-2 hover:border-orange-500 transition has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/40">
                                <input type="radio" name="payment_method" value="nagad" onchange="updatePaymentInstructions('nagad')" class="accent-orange-600">
                                <span class="font-bold text-xs text-orange-600 font-en">Nagad (নগদ)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Send Money Instructions Box -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-4 text-xs text-slate-700 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-800" id="paymentTitle">বিকাশ পার্সোনাল নম্বরে Send Money করুন:</span>
                            <button type="button" onclick="copyPaymentNumber()" class="text-brand-purple hover:underline font-bold flex items-center gap-1">
                                <i class="fa-regular fa-copy"></i>
                                <span id="copyBtnText">নম্বর কপি করুন</span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between bg-white px-3.5 py-2.5 rounded-xl border border-slate-300">
                            <span id="activePaymentNumber" class="text-base font-bold font-en tracking-wider text-slate-900">{{ $settings['bkash_number'] ?? '01934779775' }}</span>
                            <span class="text-[11px] font-bold text-pink-600 uppercase" id="paymentTypeBadge">Personal</span>
                        </div>
                        <p class="text-[11px] text-slate-600">
                            * মোট পরিশোধযোগ্য অর্থ: <strong id="modalPayableAmount" class="text-slate-900 font-en text-sm font-black">৳{{ number_format($product->offer_price, 0) }}</strong> (<span id="modalQtySummary" class="font-bold">1</span>টি লিংকের জন্য)। টাকা পাঠিয়ে নিচের বক্সে তথ্য দিন।
                        </p>
                    </div>

                    <!-- Sender Phone & TrxID -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">যে নম্বর থেকে টাকা পাঠিয়েছেন *</label>
                            <input type="tel" name="sender_phone" required placeholder="017xxxxxxxx" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-brand-purple outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">ট্রানজেকশন আইডি (TrxID) *</label>
                            <input type="text" name="trx_id" required placeholder="উদাঃ 9X7A4K3..." 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs uppercase focus:border-brand-purple outline-none font-en font-bold">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitOrderBtn" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm gradient-brand gradient-brand-hover shadow-lg shadow-purple-500/20 flex items-center justify-center gap-2 transition duration-200 btn-shine">
                        <span id="btnDefaultText" class="flex items-center gap-2">
                            <i class="fa-solid fa-lock"></i>
                            <span id="submitBtnLabel">পেমেন্ট কনফার্ম ও লিংক নিন</span>
                        </span>
                        <span id="btnLoadingText" class="hidden flex items-center gap-2">
                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                            <span>যাচাই করা হচ্ছে...</span>
                        </span>
                    </button>
                </form>
            </div>

            <!-- STAGE 2: INSTANT DELIVERY SCREEN ("আপনার ডেলিভারি") -->
            <div id="deliverySuccessSection" class="hidden text-center space-y-4">
                <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-md shadow-emerald-200">
                    <i class="fa-solid fa-check"></i>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-slate-900 font-bn">অভিনন্দন! অর্ডার সফল হয়েছে</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        অর্ডার নম্বর: <strong id="deliveredOrderNumber" class="text-slate-800 font-en"></strong> | 
                        মোট ডেলিভারি: <strong id="deliveredQtyCount" class="text-purple-700 font-mono font-bold">1</strong>টি লিংক
                    </p>
                </div>

                <!-- Multi-Link Delivery Container -->
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-between pb-1 border-b border-slate-100">
                        <span class="text-xs font-bold text-slate-800 font-bn flex items-center gap-1.5">
                            <i class="fa-solid fa-gift text-brand-purple"></i>
                            <span>আপনার অ্যাক্টিভেশন লিংকসমূহ (ক্রমানুসারে):</span>
                        </span>
                        
                        <!-- Copy All Button -->
                        <button type="button" onclick="copyAllDeliveredLinks()" id="copyAllBtn" 
                                class="px-3 py-1.5 rounded-xl gradient-brand text-white font-bold text-[11px] hover:opacity-95 transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-regular fa-copy"></i>
                            <span id="copyAllBtnText">সব লিংক একসাথে কপি</span>
                        </button>
                    </div>

                    <!-- Dynamic Links List -->
                    <div id="deliveredLinksList" class="space-y-3 max-h-72 overflow-y-auto pr-1">
                        <!-- Rendered by JavaScript -->
                    </div>
                </div>

                <!-- Activation Steps -->
                <div class="p-4 rounded-2xl bg-purple-50 text-left border border-purple-200 text-xs text-slate-700 space-y-1.5">
                    <h4 class="font-bold text-brand-purple flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>সার্ভিসটি কীভাবে অ্যাক্টিভ করবেন?</span>
                    </h4>
                    <ol class="list-decimal list-inside space-y-1 text-slate-600 text-[11px]">
                        <li>প্রতিটি লিংকের পাশের <strong>'কপি'</strong> বাটনে চাপ দিন (অথবা 'সব লিংক একসাথে কপি' করুন)।</li>
                        <li>আপনার ফোনের <strong>Google Chrome</strong> ব্রাউজারে গিয়ে লিংকটি পেস্ট করে প্রবেশ করুন।</li>
                        <li>Google One পেজ লোড হলে নিচে <strong>"Activate plan"</strong> বাটনে ট্যাপ করুন।</li>
                        <li>ব্যাস! সাথে সাথেই আপনার জিমেইলে ১৮ মাসের জন্য সফলভাবে চালু হয়ে যাবে।</li>
                    </ol>
                </div>

                <button onclick="closeCheckoutModal()" class="w-full py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    সম্পন্ন হয়েছে (উইন্ডো বন্ধ করুন)
                </button>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    const bkashNumber = "{{ $settings['bkash_number'] ?? '01934779775' }}";
    const nagadNumber = "{{ $settings['nagad_number'] ?? '01934779775' }}";
    const unitPrice = {{ $product->offer_price }};
    const maxStock = {{ $stockCount }};
    let currentQty = 1;
    let storedAllLinksText = "";

    function changeQuantity(delta) {
        let newQty = currentQty + delta;
        if (newQty < 1) newQty = 1;
        if (maxStock > 0 && newQty > maxStock) {
            alert(`দুঃখিত! বর্তমানে স্টকে সর্বোচ্চ ${maxStock}টি লিংক উপলব্ধ রয়েছে।`);
            newQty = maxStock;
        }
        currentQty = newQty;
        updateQuantityUI();
    }

    function updateQuantityUI() {
        const total = currentQty * unitPrice;
        const totalFormatted = total.toLocaleString('en-US');

        // Main Page UI
        const displayQty = document.getElementById('displayQty');
        if (displayQty) displayQty.innerText = currentQty;

        const buyBtnText = document.getElementById('buyBtnText');
        if (buyBtnText) {
            buyBtnText.innerText = `এখনই অর্ডার করুন (Buy Now) - ৳${totalFormatted}`;
        }

        // Modal UI
        const hiddenFormQty = document.getElementById('hiddenFormQty');
        if (hiddenFormQty) hiddenFormQty.value = currentQty;

        const modalQtyDisplay = document.getElementById('modalQtyDisplay');
        if (modalQtyDisplay) modalQtyDisplay.innerText = currentQty;

        const modalQtySummary = document.getElementById('modalQtySummary');
        if (modalQtySummary) modalQtySummary.innerText = currentQty;

        const modalPayableAmount = document.getElementById('modalPayableAmount');
        if (modalPayableAmount) modalPayableAmount.innerText = `৳${totalFormatted}`;

        const modalSubtitle = document.getElementById('modalSubtitle');
        if (modalSubtitle) {
            modalSubtitle.innerText = `অর্ডার সম্পন্ন করুন - ${currentQty}টি লিংক - মোট ৳${totalFormatted}`;
        }

        const submitBtnLabel = document.getElementById('submitBtnLabel');
        if (submitBtnLabel) {
            submitBtnLabel.innerText = `পেমেন্ট কনফার্ম ও ${currentQty}টি লিংক নিন (৳${totalFormatted})`;
        }
    }

    function openCheckoutModal() {
        document.getElementById('checkoutModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updateQuantityUI();

        if (typeof fbq === 'function') {
            fbq('track', 'InitiateCheckout', {
                content_name: "{{ $product->name }}",
                value: currentQty * unitPrice,
                currency: 'BDT'
            });
        }
    }

    function closeCheckoutModal() {
        document.getElementById('checkoutModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function updatePaymentInstructions(method) {
        const numberSpan = document.getElementById('activePaymentNumber');
        const titleSpan = document.getElementById('paymentTitle');
        const badgeSpan = document.getElementById('paymentTypeBadge');

        if (method === 'bkash') {
            numberSpan.innerText = bkashNumber;
            titleSpan.innerText = 'বিকাশ পার্সোনাল নম্বরে Send Money করুন:';
            badgeSpan.innerText = 'bKash Personal';
            badgeSpan.className = 'text-[11px] font-bold text-pink-600 uppercase';
        } else {
            numberSpan.innerText = nagadNumber;
            titleSpan.innerText = 'নগদ পার্সোনাল নম্বরে Send Money করুন:';
            badgeSpan.innerText = 'Nagad Personal';
            badgeSpan.className = 'text-[11px] font-bold text-orange-600 uppercase';
        }
    }

    function copyPaymentNumber() {
        const number = document.getElementById('activePaymentNumber').innerText.trim();
        navigator.clipboard.writeText(number).then(() => {
            const btnText = document.getElementById('copyBtnText');
            btnText.innerText = 'কপি হয়েছে!';
            setTimeout(() => { btnText.innerText = 'নম্বর কপি করুন'; }, 2000);
        });
    }

    async function submitOrder(e) {
        e.preventDefault();

        const form = document.getElementById('orderForm');
        const formData = new FormData(form);
        const errorBox = document.getElementById('orderErrorBox');
        const errorMessage = document.getElementById('orderErrorMessage');
        const submitBtn = document.getElementById('submitOrderBtn');
        const defaultText = document.getElementById('btnDefaultText');
        const loadingText = document.getElementById('btnLoadingText');

        errorBox.classList.add('hidden');
        submitBtn.disabled = true;
        defaultText.classList.add('hidden');
        loadingText.classList.remove('hidden');

        try {
            const response = await fetch("{{ route('order.process') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                document.getElementById('checkoutFormSection').classList.add('hidden');
                document.getElementById('deliverySuccessSection').classList.remove('hidden');

                document.getElementById('deliveredOrderNumber').innerText = data.order_number;
                document.getElementById('deliveredQtyCount').innerText = data.quantity || 1;

                storedAllLinksText = data.delivered_link || "";

                // Render Serial Links
                const linksContainer = document.getElementById('deliveredLinksList');
                linksContainer.innerHTML = "";

                const linksList = data.delivered_links && data.delivered_links.length > 0
                    ? data.delivered_links
                    : [{ serial: 1, url: data.delivered_link }];

                linksList.forEach((item) => {
                    const card = document.createElement('div');
                    card.className = "p-3.5 rounded-2xl bg-slate-900 text-left border border-slate-800 shadow-sm space-y-2";
                    card.innerHTML = `
                        <div class="flex items-center justify-between text-xs text-emerald-400 font-semibold">
                            <span class="flex items-center gap-1.5 font-bn">
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono text-[10px] font-bold">#${item.serial}</span>
                                <span>অ্যাক্টিভেশন লিংক #${item.serial}:</span>
                            </span>
                            <a href="${item.url}" target="_blank" class="text-[11px] text-purple-300 hover:text-white flex items-center gap-1 transition">
                                <span>ওপেন করুন</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                            </a>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-950 font-mono text-[11px] text-purple-200 select-all break-all border border-slate-800/80">
                            ${item.url}
                        </div>
                        <button type="button" onclick="copySingleLink('${item.url.replace(/'/g, "\\'")}', this)" 
                                class="w-full py-2 px-3 rounded-xl bg-purple-600/30 hover:bg-brand-purple text-purple-200 hover:text-white border border-purple-500/30 text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                            <i class="fa-regular fa-copy"></i>
                            <span>এই লিংকটি কপি করুন</span>
                        </button>
                    `;
                    linksContainer.appendChild(card);
                });

                if (typeof fbq === 'function') {
                    fbq('track', 'Purchase', {
                        content_name: "{{ $product->name }}",
                        value: parseFloat(data.amount),
                        currency: 'BDT'
                    });
                }
            } else {
                errorMessage.innerText = data.message || 'অর্ডারে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
                errorBox.classList.remove('hidden');
            }
        } catch (err) {
            errorMessage.innerText = 'সার্ভারে সংযোগে সমস্যা হয়েছে। অনুগ্রহ করে ইন্টারনেট চেক করুন।';
            errorBox.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            defaultText.classList.remove('hidden');
            loadingText.classList.add('hidden');
        }
    }

    function copySingleLink(url, btn) {
        navigator.clipboard.writeText(url).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-check text-emerald-400"></i><span class="text-emerald-400">কপি সম্পন্ন হয়েছে!</span>`;
            setTimeout(() => {
                btn.innerHTML = originalHtml;
            }, 2500);
        });
    }

    function copyAllDeliveredLinks() {
        if (!storedAllLinksText) return;
        navigator.clipboard.writeText(storedAllLinksText).then(() => {
            const btnText = document.getElementById('copyAllBtnText');
            btnText.innerText = 'সব লিংক কপি হয়েছে!';
            setTimeout(() => {
                btnText.innerText = 'সব লিংক একসাথে কপি';
            }, 2500);
        });
    }
</script>
@endsection
