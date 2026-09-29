@extends('admin.layout')

@section('page_title', 'নতুন ডিজিটাল প্রোডাক্ট যুক্ত করুন')
@section('page_subtitle', 'ক্যাটালগে নতুন সাবস্ক্রিপশন, সফটওয়্যার বা প্যাকেজ যোগ করুন')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto pb-12">

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        
        <div class="flex items-center gap-3.5 pb-6 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-base text-slate-900 font-bn">নতুন প্রোডাক্ট ফর্ম</h3>
                <p class="text-xs text-slate-400 font-bn">নিচের তথ্যগুলো পূরণ করে সেভ করলেই ওয়েবসাইটে প্রোডাক্টটি যুক্ত হয়ে যাবে।</p>
            </div>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Title & Subtitle -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">প্রোডাক্টের নাম (Product Name) *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="উদাঃ Netflix Premium 1 Month"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-brand-500 outline-none transition font-bn">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">সাবটাইটেল / স্লোগান (Subtitle)</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" 
                           placeholder="উদাঃ 4K Ultra HD • Private Profile"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 focus:bg-white focus:border-brand-500 outline-none transition font-bn">
                </div>
            </div>

            <!-- Prices & Badge -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">রেগুলার মূল্য (Regular ৳) *</label>
                    <input type="number" step="0.01" name="regular_price" value="{{ old('regular_price', '500') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-mono font-bold text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-pink-600 mb-1.5 font-bn">অফার মূল্য (Offer ৳) *</label>
                    <input type="number" step="0.01" name="offer_price" value="{{ old('offer_price', '250') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-pink-200 text-xs sm:text-sm font-mono font-bold text-pink-600 focus:bg-white focus:border-pink-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">ব্যাজ টেক্সট (Badge)</label>
                    <input type="text" name="badge" value="{{ old('badge', 'NEW OFFER') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition font-bn">
                </div>
            </div>

            <!-- Image Poster Upload -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">প্রোডাক্টের ৩ডি কভার ইমেজ / ব্যানার (Poster Image)</label>
                <input type="file" name="image" accept="image/*"
                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-brand-600 hover:file:bg-purple-100 transition cursor-pointer">
            </div>

            <!-- Features -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn flex items-center justify-between">
                    <span>প্যাকেজের প্রধান সুবিধাসমূহ (Features - প্রতি লাইনে একটি)</span>
                    <span class="text-[11px] text-purple-600 font-normal">১ লাইনে ১টি পয়েন্ট</span>
                </label>
                <textarea name="features" rows="4" 
                          placeholder="সুবিধা ১&#10;সুবিধা ২&#10;সুবিধা ৩"
                          class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition font-bn leading-relaxed">{{ old('features') }}</textarea>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn">পোস্টের বিস্তারিত বর্ণনা (Detailed Description)</label>
                <textarea name="description" rows="4" 
                          placeholder="এই প্রোডাক্ট বা সাবস্ক্রিপশনটি সম্পর্কে বিস্তারিত বিবরণ লিখুন..."
                          class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition font-bn leading-relaxed">{{ old('description') }}</textarea>
            </div>

            <!-- Initial Links Stock -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 font-bn flex items-center justify-between">
                    <span>অ্যাক্টিভেশন লিংক ইনপুট করুন (ঐচ্ছিক - প্রতি লাইনে ১টি)</span>
                    <span class="text-[11px] text-slate-400">পরেও দেওয়া যাবে</span>
                </label>
                <textarea name="bulk_links" rows="3" 
                          placeholder="https://...&#10;https://..." 
                          class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition leading-relaxed"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                <a href="{{ route('admin.products.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bn">বাতিল করুন</a>
                <button type="submit" 
                        class="px-8 py-3.5 rounded-2xl gradient-brand text-white font-extrabold text-xs shadow-lg shadow-purple-600/30 hover:opacity-95 transition flex items-center gap-2 font-bn">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>প্রোডাক্ট তৈরি ও পাবলিশ করুন</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
