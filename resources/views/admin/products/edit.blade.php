@extends('admin.layout')

@section('page_title', 'প্রোডাক্ট এডিট: ' . $product->name)
@section('page_subtitle', 'পোস্টের বিবরণ, টাইটেল, ইমেজ, প্রাইস ও লিংক পরিবর্তন করুন')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Top Breadcrumb & Status Banner -->
    <div class="p-5 rounded-3xl gradient-dark-card border border-slate-800 text-white flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-purple-500/20 border border-purple-500/30 text-purple-300 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-extrabold text-base font-bn text-white">পোস্ট এডিটর: {{ $product->name }}</span>
                    @if($product->is_active)
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[10px] font-bold font-bn">ইন স্টক</span>
                    @else
                    <span class="px-2 py-0.5 rounded-full bg-rose-500/20 border border-rose-500/30 text-rose-300 text-[10px] font-bold font-bn">স্টক শেষ</span>
                    @endif
                </div>
                <p class="text-xs text-slate-300 font-bn mt-0.5">
                    এখানে যে তথ্যগুলো পরিবর্তন করে সেভ করবেন, তা সরাসরি ওয়েবসাইটে এবং কাস্টমারদের অর্ডার বাটনে শো করবে।
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 text-xs font-semibold font-bn transition">
                &larr; তালিকায় ফিরে যান
            </a>
            <a href="{{ route('product.details', $product->slug) }}" target="_blank" 
               class="px-4 py-2.5 rounded-xl bg-brand-purple hover:bg-purple-600 text-white text-xs font-bold font-bn transition flex items-center gap-1.5 shadow-md">
                <span>লাইভ পেজ দেখুন</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    <!-- Main Grid: Left Form Controls (7 Cols) & Right Simulator (5 Cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Form Area (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- 1. Title, Subtitle & Stock State -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-heading text-brand-600"></i>
                        <span>পোস্টের শিরোনাম ও স্ট্যাটাস</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                            প্রোডাক্টের নাম / পোস্টের শিরোনাম (Product Title)
                        </label>
                        <input type="text" id="editName" name="name" value="{{ old('name', $product->name) }}" required
                               oninput="liveUpdate()"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition font-bn">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                            সংক্ষিপ্ত সাবটাইটেল (Subtitle)
                        </label>
                        <input type="text" id="editSubtitle" name="subtitle" value="{{ old('subtitle', $product->subtitle) }}"
                               oninput="liveUpdate()"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 focus:bg-white focus:border-brand-500 outline-none transition font-bn">
                    </div>

                    <!-- In Stock Switch -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-xs text-slate-800 block font-bn">ওয়েবসাইটে বিক্রির অবস্থা (Stock Availability)</span>
                            <span class="text-[11px] text-slate-500 font-bn">অন রাখলে কাস্টমাররা Buy Now বাটন দেখতে পাবে, অফ রাখলে Out of Stock দেখাবে।</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>

                <!-- 2. Pricing & Badge -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-bangladeshi-taka-sign text-emerald-600"></i>
                        <span>মূল্য নির্ধারণ ও ডিসকাউন্ট ব্যাজ</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                                রেগুলার মূল্য (Regular ৳)
                            </label>
                            <input type="number" step="0.01" id="editRegPrice" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" required
                                   oninput="liveUpdate()"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-mono font-bold text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-pink-600 mb-1.5 font-bn">
                                অফার মূল্য (Offer ৳)
                            </label>
                            <input type="number" step="0.01" id="editOfferPrice" name="offer_price" value="{{ old('offer_price', $product->offer_price) }}" required
                                   oninput="liveUpdate()"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-pink-200 text-xs sm:text-sm font-mono font-bold text-pink-600 focus:bg-white focus:border-pink-600 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                                ব্যাজ টেক্সট (Badge)
                            </label>
                            <input type="text" id="editBadge" name="badge" value="{{ old('badge', $product->badge) }}"
                                   oninput="liveUpdate()"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition font-bn">
                        </div>
                    </div>
                </div>

                <!-- 3. Image Poster Upload -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-image text-brand-600"></i>
                        <span>প্রোডাক্টের কভার ইমেজ / ৩ডি পোস্টার</span>
                    </h3>

                    <div class="flex flex-col sm:flex-row items-center gap-5">
                        <div class="relative w-28 h-28 rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 shrink-0 shadow-sm">
                            <img id="currentThumb" src="{{ $product->image_url }}" alt="Poster" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 inset-x-1 text-center bg-black/70 text-white text-[9px] py-0.5 rounded font-bn">
                                বর্তমান ছবি
                            </span>
                        </div>

                        <div class="flex-grow w-full space-y-2">
                            <label class="block text-xs font-bold text-slate-700 font-bn">
                                নতুন ইমেজ সিলেক্ট করুন (JPG, PNG বা WebP)
                            </label>
                            <input type="file" name="image" id="editImageInput" accept="image/*"
                                   onchange="previewImage(this)"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-brand-600 hover:file:bg-purple-100 transition cursor-pointer">
                            <p class="text-[11px] text-slate-400 font-bn">
                                * নতুন কোনো ইমেজ দিলে তা সাথে সাথে রিপ্লেস হয়ে সাইটে প্রদর্শিত হবে।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 4. Features & Description -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-list-check text-brand-600"></i>
                        <span>ফিচার তালিকা ও বিস্তারিত বিবরণ</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn flex items-center justify-between">
                            <span>প্যাকেজের প্রধান সুবিধাসমূহ (Features - প্রতি লাইনে একটি)</span>
                            <span class="text-[11px] text-purple-600 font-normal">১ লাইনে ১টি পয়েন্ট</span>
                        </label>
                        @php
                            $featuresStr = is_array($product->features) ? implode("\n", $product->features) : '';
                        @endphp
                        <textarea id="editFeatures" name="features" rows="5" 
                                  oninput="liveUpdate()"
                                  placeholder="ফিচার ১&#10;ফিচার ২&#10;ফিচার ৩"
                                  class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition font-bn leading-relaxed">{{ old('features', $featuresStr) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">
                            পোস্টের বিস্তারিত বর্ণনা (Detailed Description)
                        </label>
                        <textarea id="editDescription" name="description" rows="5" 
                                  placeholder="এই প্রোডাক্ট বা সাবস্ক্রিপশনটি সম্পর্কে বিস্তারিত বিবরণ এখানে লিখুন..."
                                  class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition font-bn leading-relaxed">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- 5. Single-Use Activation Links Pool -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-extrabold text-sm text-slate-900 font-bn flex items-center gap-2">
                            <i class="fa-solid fa-link text-emerald-600"></i>
                            <span>অ্যাক্টিভেশন লিংক স্টক পুল (Link Stock)</span>
                        </h3>
                        <span class="px-3 py-1 rounded-full text-xs font-bold font-mono {{ $availableLinks > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                            স্টকে আছে: {{ $availableLinks }}টি | বিক্রি হয়েছে: {{ $soldLinks }}টি
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn flex items-center justify-between">
                            <span>নতুন লিংক যোগ করতে এখানে পেস্ট করুন (প্রতি লাইনে ১টি)</span>
                            <span class="text-[11px] text-slate-400">ঐচ্ছিক</span>
                        </label>
                        <textarea name="bulk_links" rows="3" 
                                  placeholder="https://...&#10;https://..." 
                                  class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition leading-relaxed"></textarea>
                    </div>
                </div>

                <!-- Save Button -->
                <div>
                    <button type="submit" 
                            class="w-full py-4 px-8 rounded-2xl gradient-brand text-white font-extrabold text-sm shadow-xl shadow-purple-600/30 hover:opacity-95 transition flex items-center justify-center gap-2 font-bn">
                        <i class="fa-solid fa-floppy-disk text-base"></i>
                        <span>পোস্টের সমস্ত পরিবর্তন সেভ করুন (Save Changes)</span>
                    </button>
                </div>

            </form>
        </div>

        <!-- Right Column: Real-time Customer Simulator (5 Cols) -->
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
                    <!-- Image Box -->
                    <div class="relative aspect-square overflow-hidden bg-slate-900 p-2">
                        <span id="simBadge" class="absolute top-4 left-4 z-10 bg-gradient-to-r from-purple-600 to-pink-500 text-white text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider shadow-md">
                            {{ $product->badge }}
                        </span>

                        <img id="simImg" src="{{ $product->image_url }}" alt="Preview" 
                             class="w-full h-full object-cover rounded-xl">

                        <div class="absolute bottom-4 left-4 z-10 bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded shadow flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                            <span>ইন স্টক ({{ $availableLinks }}টি বাকি)</span>
                        </div>
                    </div>

                    <!-- Details Box -->
                    <div class="p-5 space-y-3">
                        <h3 id="simTitle" class="font-black text-base text-slate-900 font-bn leading-tight">
                            {{ $product->name }}
                        </h3>

                        <p id="simSubtitle" class="text-xs text-slate-500 font-bn">
                            {{ $product->subtitle }}
                        </p>

                        <!-- Price Tag -->
                        <div class="flex items-baseline gap-2 pt-1 border-t border-slate-100">
                            <span id="simOfferPrice" class="font-black font-mono text-xl text-slate-900">
                                ৳{{ number_format($product->offer_price, 0) }}
                            </span>
                            <span id="simRegPrice" class="font-mono text-xs text-slate-400 line-through">
                                ৳{{ number_format($product->regular_price, 0) }}
                            </span>
                            <span id="simDiscountBadge" class="ml-auto text-[10px] font-bold text-pink-600 bg-pink-50 px-2 py-0.5 rounded font-bn">
                                ছাড়
                            </span>
                        </div>

                        <!-- Live Features -->
                        <div class="pt-2 border-t border-slate-100 space-y-1.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-bn">প্যাকেজ ফিচারস:</span>
                            <div id="simFeaturesList" class="space-y-1 text-[11px] text-slate-600 font-bn">
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

                        <!-- Simulated Buy Button with LIVE Price -->
                        <div class="pt-2">
                            <div class="w-full py-2.5 rounded-xl gradient-brand text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md font-bn">
                                <i class="fa-solid fa-bolt text-yellow-300"></i>
                                <span>অর্ডার করুন (Buy Now) - <span id="simBtnPrice">৳{{ number_format($product->offer_price, 0) }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-center text-[11px] text-slate-400 font-bn">
                    * কাস্টমাররা ওয়েবসাইটে ঢুকলে ঠিক এই রূপটি দেখতে পাবে।
                </p>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function liveUpdate() {
        const name = document.getElementById('editName').value;
        const subtitle = document.getElementById('editSubtitle').value;
        const reg = parseFloat(document.getElementById('editRegPrice').value) || 0;
        const off = parseFloat(document.getElementById('editOfferPrice').value) || 0;
        const badge = document.getElementById('editBadge').value;
        const featText = document.getElementById('editFeatures').value;

        document.getElementById('simTitle').innerText = name || 'প্রোডাক্টের নাম';
        document.getElementById('simSubtitle').innerText = subtitle || '';
        document.getElementById('simRegPrice').innerText = `৳${reg}`;
        document.getElementById('simOfferPrice').innerText = `৳${off}`;
        document.getElementById('simBtnPrice').innerText = `৳${off}`;
        document.getElementById('simBadge').innerText = badge || 'OFFER';

        if (reg > 0 && off > 0 && reg > off) {
            const pct = Math.round(((reg - off) / reg) * 100);
            document.getElementById('simDiscountBadge').innerText = `${pct}% ছাড়`;
            document.getElementById('simDiscountBadge').classList.remove('hidden');
        } else {
            document.getElementById('simDiscountBadge').classList.add('hidden');
        }

        const simList = document.getElementById('simFeaturesList');
        simList.innerHTML = '';
        const lines = featText.split(/\r\n|\r|\n/).filter(l => l.trim().length > 0).slice(0, 4);
        lines.forEach(line => {
            const d = document.createElement('div');
            d.className = 'flex items-center gap-1.5';
            d.innerHTML = `<i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> <span>${line}</span>`;
            simList.appendChild(d);
        });
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('simImg').src = e.target.result;
                document.getElementById('currentThumb').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    liveUpdate();
</script>
@endpush
