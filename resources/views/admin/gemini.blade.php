@extends('admin.layout')

@section('page_title', 'জেমিনাই প্রো অফার ও পোস্ট কন্ট্রোল')
@section('page_subtitle', 'ফেসবুক অ্যাড চালানোর জন্য পোস্টের কন্টেন্ট, ইমেজ, মূল্য ও লিংক নিয়ন্ত্রণ করুন')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Top Banner: Direct Connection Notification -->
    <div class="p-5 rounded-3xl gradient-dark-card border border-purple-500/30 text-white flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-amber-300 flex items-center justify-center text-xl shrink-0 shadow-lg">
                <i class="fa-solid fa-sparkles"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-extrabold text-base font-bn text-white">লাইভ অ্যাডস অফার কন্ট্রোল সেন্টার</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[10px] font-bold font-mono">1-CLICK SYNC</span>
                </div>
                <p class="text-xs text-slate-300 font-bn mt-0.5">
                    এখানে কোনো তথ্য পরিবর্তন করে নিচে <strong>"সংরক্ষণ করুন"</strong> বাটনে চাপলেই তা সাথে সাথে মূল ওয়েবসাইট ও কাস্টমারদের স্ক্রিনে লাইভ পরিবর্তন হয়ে যাবে!
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <button type="button" onclick="copyText('{{ route('product.details', $product->slug) }}', this)" 
                    class="px-4 py-2.5 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition flex items-center gap-2 font-bn shadow-md border border-purple-400/40"
                    title="ফেসবুক অ্যাডের জন্য প্রোডাক্ট লিংক কপি করুন">
                <i class="fa-regular fa-copy text-xs"></i>
                <span>অ্যাডের লিংক কপি করুন</span>
            </button>
            <a href="{{ route('product.details', $product->slug) }}" target="_blank" 
               class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white border border-white/15 text-xs font-bold transition flex items-center gap-2 font-bn">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>লাইভ দেখুন</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Left Form Controls & Right Live Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Edit Form (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <form action="{{ route('admin.gemini.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- 1. Basic Info Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-pen-to-square text-brand-600"></i>
                        <span>পোস্টের শিরোনাম ও ব্যাজ সেটিংস</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                            প্রোডাক্টের নাম / পোস্টের শিরোনাম (Product Title)
                        </label>
                        <input type="text" id="inputName" name="name" value="{{ old('name', $product->name) }}" required
                               oninput="updatePreview()"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition font-bn">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                            সাবটাইটেল / সংক্ষিপ্ত স্লোগান (Subtitle)
                        </label>
                        <input type="text" id="inputSubtitle" name="subtitle" value="{{ old('subtitle', $product->subtitle) }}"
                               oninput="updatePreview()"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition font-bn">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                                রেগুলার মূল্য (Regular ৳)
                            </label>
                            <input type="number" step="0.01" id="inputRegPrice" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" required
                                   oninput="updatePreview()"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-mono font-bold text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-pink-600 mb-1.5 font-bn">
                                অফার মূল্য (Offer ৳)
                            </label>
                            <input type="number" step="0.01" id="inputOfferPrice" name="offer_price" value="{{ old('offer_price', $product->offer_price) }}" required
                                   oninput="updatePreview()"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-pink-200 text-xs sm:text-sm font-mono font-bold text-pink-600 focus:bg-white focus:border-pink-600 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                                ব্যাজ টেক্সট (Badge)
                            </label>
                            <input type="text" id="inputBadge" name="badge" value="{{ old('badge', $product->badge) }}"
                                   oninput="updatePreview()"
                                   placeholder="HOT DEAL / ৫০% ছাড়"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition font-bn">
                        </div>
                    </div>
                </div>

                <!-- 2. Image Upload Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-image text-brand-600"></i>
                        <span>পোস্টের কভার ইমেজ / পোস্টার পরিবর্তন</span>
                    </h3>

                    <div class="flex flex-col sm:flex-row items-center gap-5">
                        <!-- Current Image Thumbnail -->
                        <div class="relative w-28 h-28 rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 shrink-0 shadow-sm">
                            <img id="currentImageThumb" src="{{ $product->image_url }}" alt="Current Poster" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 inset-x-1 text-center bg-black/70 text-white text-[9px] py-0.5 rounded font-bn">
                                বর্তমান ইমেজ
                            </span>
                        </div>

                        <!-- Upload New Image Controls -->
                        <div class="flex-grow w-full space-y-2">
                            <label class="block text-xs font-bold text-slate-700 font-bn">
                                নতুন ইমেজ সিলেক্ট করুন (JPG, PNG বা WebP)
                            </label>
                            <input type="file" name="image" id="imageFileInput" accept="image/*"
                                   onchange="previewNewImage(this)"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-brand-600 hover:file:bg-purple-100 transition cursor-pointer">
                            <p class="text-[11px] text-slate-400 font-bn">
                                * ফেসবুক অ্যাডের জন্য কোনো নতুন ইমেজ বা ব্যানার থাকলে এখানে আপলোড করলে সাথে সাথে ওয়েবসাইটে পরিবর্তন হয়ে যাবে।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3. Features & Description Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-list-check text-brand-600"></i>
                        <span>ফিচার তালিকা ও বিস্তারিত বিবরণ</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn flex items-center justify-between">
                            <span>প্যাকেজের প্রধান সুবিধাসমূহ (Features - প্রতি লাইনে একটি)</span>
                            <span class="text-[11px] text-purple-600 font-normal">১ লাইনে ১টি ফিচার</span>
                        </label>
                        @php
                            $featuresText = is_array($product->features) ? implode("\n", $product->features) : '';
                        @endphp
                        <textarea id="inputFeatures" name="features" rows="5" 
                                  oninput="updatePreview()"
                                  placeholder="১৮ মাসের অফিসিয়াল সাবস্ক্রিপশন&#10;Google Gemini 1.5 Pro মডেল অ্যাক্সেস&#10;২ মিলিয়ন টোকেন সুপার কনটেক্সট&#10;অ্যাডভান্সড কোডিং ও ডেটা অ্যানালিসিস&#10;১ সেকেন্ডে ইনস্ট্যান্ট অ্যাক্টিভেশন লিংক"
                                  class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition font-bn leading-relaxed">{{ old('features', $featuresText) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                            পোস্টের বিস্তারিত বর্ণনা (Detailed Description)
                        </label>
                        <textarea id="inputDescription" name="description" rows="4" 
                                  placeholder="এই অফার সম্পর্কে বিস্তারিত তথ্য এখানে লিখতে পারেন..."
                                  class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition font-bn leading-relaxed">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- 4. Quick Stock Link Upload Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2">
                            <i class="fa-solid fa-link text-emerald-600"></i>
                            <span>অ্যাক্টিভেশন লিংক স্টক পুল (Quick Stock Links)</span>
                        </h3>
                        <span class="px-3 py-1 rounded-full text-xs font-bold font-mono {{ $availableLinks > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                            স্টকে আছে: {{ $availableLinks }}টি
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn flex items-center justify-between">
                            <span>নতুন লিংক যোগ করতে এখানে পেস্ট করুন (ঐচ্ছিক)</span>
                            <span class="text-[11px] text-slate-400">প্রতি লাইনে ১টি লিংক</span>
                        </label>
                        <textarea name="bulk_links" rows="3" 
                                  placeholder="https://serviceactivation.google.com/subscription/new/...&#10;https://serviceactivation.google.com/subscription/new/..." 
                                  class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition leading-relaxed"></textarea>
                        <p class="text-[11px] text-slate-400 mt-1 font-bn">
                            * কাস্টমার অর্ডার দিলে এই কিউ থেকেই একেকজন একেকটি ইউনিক লিংক পাবে।
                        </p>
                    </div>
                </div>

                <!-- 4. Tutorial Video Configuration -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-circle-play text-rose-600 text-lg"></i>
                        <span>অ্যাক্টিভেশন টিউটোরিয়াল ভিডিও গাইড (Tutorial Video)</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn flex items-center justify-between">
                            <span>YouTube ভিডিও লিংক অথবা HTML Embed কোড</span>
                            <span class="text-[11px] text-brand-600 font-mono">YouTube URL / Iframe</span>
                        </label>
                        <textarea name="tutorial_video_url" rows="2" 
                                  placeholder="উদাঃ https://youtu.be/xxxx অথবা https://www.youtube.com/watch?v=xxxx অথবা <iframe>...</iframe>"
                                  class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-rose-500 outline-none transition">{{ $settings['tutorial_video_url'] ?? '' }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1 font-bn">
                            * কাস্টমারদের অর্ডার সফল হওয়ার পর স্ক্রিনে ভিডিও বাটন দেখাবে। বক্স খালি রাখলে কোনো বাটন দেখাবে না।
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                            বাটন টেক্সট (Button Label)
                        </label>
                        <input type="text" name="tutorial_video_btn_text" 
                               value="{{ $settings['tutorial_video_btn_text'] ?? 'ভিডিও দেখুন: ১ মিনিটে চালু করার নিয়ম' }}" 
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bn text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                    </div>
                </div>

                <!-- Save Action Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-4 px-8 rounded-2xl gradient-brand text-white font-extrabold text-sm shadow-xl shadow-purple-600/30 hover:opacity-95 transition flex items-center justify-center gap-2 font-bn">
                        <i class="fa-solid fa-floppy-disk text-base"></i>
                        <span>জেমিনাই অফার ও পোস্টের সকল পরিবর্তন সংরক্ষণ করুন</span>
                    </button>
                </div>

            </form>
        </div>

        <!-- Right Column: Live Customer Preview (5 Cols) -->
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-20">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-md p-6 space-y-5">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider font-bn">লাইভ ইউজার প্রিভিউ</h4>
                    </div>
                    <span class="text-[10px] text-purple-600 font-bold bg-purple-50 px-2 py-0.5 rounded font-mono">
                        Real-time Simulator
                    </span>
                </div>

                <!-- Simulated Product Card -->
                <div class="rounded-2xl border border-slate-200 shadow-sm overflow-hidden bg-white">
                    <!-- Image Area -->
                    <div class="relative aspect-square overflow-hidden bg-slate-900 p-2">
                        <span id="previewBadge" class="absolute top-4 left-4 z-10 bg-gradient-to-r from-purple-600 to-pink-500 text-white text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider shadow-md">
                            {{ $product->badge }}
                        </span>

                        <img id="livePreviewImg" src="{{ $product->image_url }}" alt="Preview" 
                             class="w-full h-full object-cover rounded-xl">

                        <div class="absolute bottom-4 left-4 z-10 bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded shadow flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                            <span>ইন স্টক ({{ $availableLinks }}টি বাকি)</span>
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="p-5 space-y-3">
                        <h3 id="previewTitle" class="font-black text-base text-slate-900 font-bn leading-tight">
                            {{ $product->name }}
                        </h3>

                        <p id="previewSubtitle" class="text-xs text-slate-500 font-bn">
                            {{ $product->subtitle }}
                        </p>

                        <!-- Price Tag -->
                        <div class="flex items-baseline gap-2 pt-1 border-t border-slate-100">
                            <span id="previewOfferPrice" class="font-black font-mono text-xl text-slate-900">
                                ৳{{ number_format($product->offer_price, 0) }}
                            </span>
                            <span id="previewRegPrice" class="font-mono text-xs text-slate-400 line-through">
                                ৳{{ number_format($product->regular_price, 0) }}
                            </span>
                            <span id="previewDiscountBadge" class="ml-auto text-[10px] font-bold text-pink-600 bg-pink-50 px-2 py-0.5 rounded font-bn">
                                ৫০% ছাড়
                            </span>
                        </div>

                        <!-- Live Features Snippet -->
                        <div class="pt-2 border-t border-slate-100 space-y-1.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-bn">প্যাকেজ ফিচারস:</span>
                            <div id="previewFeaturesList" class="space-y-1 text-[11px] text-slate-600 font-bn">
                                @if(is_array($product->features))
                                    @foreach(array_slice($product->features, 0, 4) as $feat)
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i>
                                        <span>{{ $feat }}</span>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Buy Button -->
                        <div class="pt-2">
                            <div class="w-full py-2.5 rounded-xl gradient-brand text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md font-bn">
                                <i class="fa-solid fa-bolt text-yellow-300"></i>
                                <span>অর্ডার করুন (Buy Now)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-center text-[11px] text-slate-400 font-bn">
                    * কাস্টমাররা ওয়েবসাইটে ঢুকলে ঠিক এই পোস্টটি দেখতে পাবে।
                </p>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function updatePreview() {
        const name = document.getElementById('inputName').value;
        const subtitle = document.getElementById('inputSubtitle').value;
        const reg = parseFloat(document.getElementById('inputRegPrice').value) || 0;
        const off = parseFloat(document.getElementById('inputOfferPrice').value) || 0;
        const badge = document.getElementById('inputBadge').value;
        const featuresText = document.getElementById('inputFeatures').value;

        document.getElementById('previewTitle').innerText = name || 'প্রোডাক্টের নাম';
        document.getElementById('previewSubtitle').innerText = subtitle || '';
        document.getElementById('previewRegPrice').innerText = `৳${reg}`;
        document.getElementById('previewOfferPrice').innerText = `৳${off}`;
        document.getElementById('previewBadge').innerText = badge || 'OFFER';

        if (reg > 0 && off > 0 && reg > off) {
            const pct = Math.round(((reg - off) / reg) * 100);
            document.getElementById('previewDiscountBadge').innerText = `${pct}% ছাড়`;
            document.getElementById('previewDiscountBadge').classList.remove('hidden');
        } else {
            document.getElementById('previewDiscountBadge').classList.add('hidden');
        }

        // Features list
        const featList = document.getElementById('previewFeaturesList');
        featList.innerHTML = '';
        const lines = featuresText.split(/\r\n|\r|\n/).filter(l => l.trim().length > 0).slice(0, 4);
        lines.forEach(line => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-1.5';
            div.innerHTML = `<i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> <span>${line}</span>`;
            featList.appendChild(div);
        });
    }

    function previewNewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('livePreviewImg').src = e.target.result;
                document.getElementById('currentImageThumb').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    updatePreview();
</script>
@endpush
