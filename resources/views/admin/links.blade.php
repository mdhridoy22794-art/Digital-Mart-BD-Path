@extends('admin.layout')

@section('page_title', 'লিংক স্টক পুল (Stock Management)')
@section('page_subtitle', 'জেমিনাই প্রো ও ডিজিটাল প্রোডাক্টের অ্যাক্টিভেশন লিংক ইনভেন্টরি ও ডিলিট/যুক্ত করার সুবিধা')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- 1. Bulk Upload Box -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-6 border-b border-slate-100">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl gradient-brand text-white flex items-center justify-center text-xl shadow-glow-purple">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 font-bn">একসাথে বাল্ক লিংক ইনপুট করুন (Bulk Stock Upload)</h3>
                    <p class="text-xs text-slate-400 font-bn">প্রতি লাইনে একটি করে অ্যাক্টিভেশন লিংক পেস্ট করুন। কিউ সিস্টেমে প্রতিটি লিংক একবারই ক্রমানুসারে (FIFO) ডেলিভারি হবে।</p>
                </div>
            </div>

            <!-- Live Counter Badge & Paste -->
            <div class="flex items-center gap-2">
                <span id="detectedLinkBadge" class="hidden px-3.5 py-1.5 rounded-full bg-purple-50 text-brand-600 border border-purple-200 text-xs font-bold font-mono">
                    <span id="detectedLinkCount">0</span>টি লিংক শনাক্ত
                </span>
                <button type="button" onclick="pasteFromClipboard()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold font-bn transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-regular fa-paste text-purple-600"></i>
                    <span>ক্লিপবোর্ড থেকে পেস্ট</span>
                </button>
            </div>
        </div>

        <form action="{{ route('admin.links.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Product Selection -->
            @if(isset($products) && $products->count() > 1)
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 pb-2">
                <label class="text-xs font-bold text-slate-700 font-bn">কোন প্রোডাক্টের জন্য লিংক যুক্ত করছেন?</label>
                <select name="product_id" class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 outline-none focus:border-brand-500">
                    @foreach($products as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->name }} (বর্তমান উপলব্ধ স্টক: {{ $prod->stock_count }}টি)</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="relative">
                <textarea id="bulkLinksTextarea" name="bulk_links" rows="6" required 
                          oninput="countLinks(this)"
                          placeholder="https://serviceactivation.google.com/subscription/new/...&#10;https://serviceactivation.google.com/subscription/new/...&#10;https://serviceactivation.google.com/subscription/new/..." 
                          class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200/90 font-mono text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition duration-200 leading-relaxed"></textarea>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                <div class="flex items-center gap-3 text-xs text-slate-500 font-bn">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>স্মার্ট ডুপ্লিকেট লিংক ফিল্টার</span>
                    </div>
                    <div class="hidden sm:flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>অটোমেটিক সিরিয়াল ফার্স্ট-ইন ফার্স্ট-আউট (FIFO) কিউ</span>
                    </div>
                </div>

                <button type="submit" 
                        class="px-7 py-3 rounded-2xl gradient-brand text-white font-bold text-xs shadow-lg shadow-purple-600/30 hover:opacity-95 transition flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform"></i>
                    <span class="font-bn text-sm">স্টকে লিংকগুলো সংরক্ষণ করুন</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Stock List & Status Table with Clear & Bulk Delete Controls -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header & Segmented Filter Tabs + Danger Actions -->
        <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center">
                    <i class="fa-solid fa-list-check text-base"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-slate-900 font-bn">সকল স্টোরকৃত অ্যাক্টিভেশন লিংক</h3>
                    <p class="text-xs text-slate-400 font-bn">বর্তমান ডাটাবেজের সম্পূর্ণ ইনভেন্টরি পুল (যেকোনো লিংক ডিলিট বা এডিট করতে পারেন)</p>
                </div>
            </div>

            <!-- Right Controls: Filter tabs & Clear Unsold Button -->
            <div class="flex flex-wrap items-center gap-3">
                
                <!-- Segmented Control Tabs -->
                <div class="flex items-center p-1 rounded-2xl bg-slate-100 border border-slate-200/80 text-xs font-semibold">
                    
                    <a href="{{ route('admin.links', ['status' => 'all']) }}" 
                       class="px-3.5 py-1.5 rounded-xl transition-all duration-200 flex items-center gap-1.5 {{ $filter === 'all' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                        <span class="font-bn">সবগুলো</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-mono {{ $filter === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-200 text-slate-600' }}">
                            {{ $links->total() }}
                        </span>
                    </a>

                    <a href="{{ route('admin.links', ['status' => 'available']) }}" 
                       class="px-3.5 py-1.5 rounded-xl transition-all duration-200 flex items-center gap-1.5 {{ $filter === 'available' ? 'bg-white text-emerald-700 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                        <span class="font-bn">স্টকে আছে</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-mono {{ $filter === 'available' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $availableCount }}
                        </span>
                    </a>

                    <a href="{{ route('admin.links', ['status' => 'sold']) }}" 
                       class="px-3.5 py-1.5 rounded-xl transition-all duration-200 flex items-center gap-1.5 {{ $filter === 'sold' ? 'bg-white text-purple-700 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                        <span class="font-bn">বিক্রি হয়েছে</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-mono {{ $filter === 'sold' ? 'bg-brand-600 text-white' : 'bg-purple-100 text-purple-800' }}">
                            {{ $soldCount }}
                        </span>
                    </a>
                </div>

                <!-- 1-Click Clear All Available Unsold Links -->
                @if($availableCount > 0)
                <form action="{{ route('admin.links.clear-unsold') }}" method="POST"
                      onsubmit="return confirm('সতর্কবার্তা: আপনি কি নিশ্চিত যে সমস্ত অবিক্রীত (Available {{ $availableCount }}টি) লিংক ডাটাবেজ থেকে মুছে ফেলতে চান?');">
                    @csrf
                    <button type="submit" 
                            class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold font-bn transition flex items-center gap-1.5 shadow-sm"
                            title="স্টকে থাকা সব অবিক্রীত লিংক মুছে ফেলুন">
                        <i class="fa-solid fa-trash-can text-rose-600"></i>
                        <span>সব অবিক্রীত লিংক মুছুন</span>
                    </button>
                </form>
                @endif

            </div>

        </div>

        <!-- Bulk Action Form & Table -->
        <form id="bulkDeleteForm" action="{{ route('admin.links.bulk-delete') }}" method="POST" 
              onsubmit="return confirm('আপনি কি নিশ্চিত নির্বাচিত লিংকগুলো ডাটাবেজ থেকে মুছে ফেলতে চান?');">
            @csrf

            <!-- Bulk Delete Bar (Dynamic) -->
            <div id="bulkActionsBar" class="hidden bg-purple-50/80 px-6 py-3 border-b border-purple-100 flex items-center justify-between">
                <span class="text-xs font-bold text-brand-700 font-bn flex items-center gap-2">
                    <i class="fa-solid fa-check-double text-purple-600"></i>
                    <span>নির্বাচিত হয়েছে: <span id="selectedCount" class="font-mono text-sm font-black">0</span>টি লিংক</span>
                </span>

                <button type="submit" 
                        class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold font-bn transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>নির্বাচিতগুলো ডিলিট করুন</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                        <tr>
                            <th class="py-4 px-4 w-12 text-center">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" 
                                       class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-purple-500 cursor-pointer">
                            </th>
                            <th class="py-4 px-4 w-16 font-bn">আইডি</th>
                            <th class="py-4 px-6 font-bn">অ্যাক্টিভেশন লিংক ও কুইক প্রিভিউ</th>
                            <th class="py-4 px-6 w-32 font-bn">বর্তমান অবস্থা</th>
                            <th class="py-4 px-6 font-bn">কাস্টমার ফোন নম্বর</th>
                            <th class="py-4 px-6 font-bn">ডেলিভারির তারিখ</th>
                            <th class="py-4 px-6 w-20 text-center font-bn">ডিলিট</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($links as $link)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            
                            <!-- Checkbox -->
                            <td class="py-4 px-4 text-center">
                                <input type="checkbox" name="selected_links[]" value="{{ $link->id }}" 
                                       onchange="updateBulkBar()" 
                                       class="link-checkbox w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-purple-500 cursor-pointer">
                            </td>

                            <!-- ID -->
                            <td class="py-4 px-4">
                                <span class="font-mono text-slate-400 font-semibold">#{{ $link->id }}</span>
                            </td>

                            <!-- Link with Copy & Open -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2 max-w-xl">
                                    <span class="font-mono text-[11px] text-slate-800 truncate block max-w-md bg-slate-100/70 px-2.5 py-1.5 rounded-lg border border-slate-200 select-all" title="{{ $link->link_url }}">
                                        {{ $link->link_url }}
                                    </span>
                                    
                                    <button type="button" onclick="copyText('{{ $link->link_url }}', this)" 
                                            class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-brand-500 text-slate-600 hover:text-brand-600 text-[10px] font-bold font-mono transition shrink-0 shadow-sm"
                                            title="Copy Link">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>

                                    <a href="{{ $link->link_url }}" target="_blank" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-slate-100 transition shrink-0" 
                                       title="Test Link in New Tab">
                                       <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-6">
                                @if($link->status === 'available')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>ইন স্টক (Available)</span>
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                    <span>বিক্রি হয়েছে (Sold)</span>
                                </span>
                                @endif
                            </td>

                            <!-- Delivered To Phone -->
                            <td class="py-4 px-6 font-mono text-slate-700 font-semibold">
                                @if($link->delivered_to_phone)
                                <span class="inline-flex items-center gap-1.5 text-xs text-slate-800">
                                    <i class="fa-solid fa-phone text-slate-400 text-[10px]"></i>
                                    {{ $link->delivered_to_phone }}
                                </span>
                                @else
                                <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <!-- Delivered Date -->
                            <td class="py-4 px-6 font-mono text-[11px] text-slate-400 whitespace-nowrap">
                                @if($link->delivered_at)
                                <span class="text-slate-800 font-semibold">{{ $link->delivered_at->format('d M, Y') }}</span><br>
                                <span>{{ $link->delivered_at->format('h:i A') }}</span>
                                @else
                                <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <!-- Delete Action (Available for ALL links now) -->
                            <td class="py-4 px-6 text-center">
                                <button type="button" 
                                        onclick="deleteSingleLink('{{ $link->id }}', '{{ $link->status }}', '{{ $link->delivered_to_phone }}')"
                                        class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-500 hover:text-white border border-rose-200/60 hover:border-rose-600 transition flex items-center justify-center mx-auto shadow-sm" 
                                        title="মুছে ফেলুন">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 px-6 text-center text-slate-400 font-bn">
                                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fa-solid fa-link-slash"></i>
                                </div>
                                <span class="text-sm font-semibold block text-slate-600">কোনো লিংক পাওয়া যায়নি</span>
                                <span class="text-xs text-slate-400">উপরের বক্সে লিংক পেস্ট করে "স্টকে লিংকগুলো সংরক্ষণ করুন" বাটনে চাপুন।</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Hidden form for single delete -->
        <form id="singleDeleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <!-- Pagination -->
        @if($links->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $links->links() }}
        </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script>
    function countLinks(textarea) {
        const text = textarea.value.trim();
        const badge = document.getElementById('detectedLinkBadge');
        const countSpan = document.getElementById('detectedLinkCount');
        if (!text) {
            badge.classList.add('hidden');
            return;
        }
        const lines = text.split(/\r\n|\r|\n/).filter(line => line.trim().length > 0);
        countSpan.innerText = lines.length;
        badge.classList.remove('hidden');
    }

    async function pasteFromClipboard() {
        try {
            const text = await navigator.clipboard.readText();
            const textarea = document.getElementById('bulkLinksTextarea');
            textarea.value = text;
            countLinks(textarea);
            textarea.focus();
        } catch (err) {
            alert('ক্লিপবোর্ড থেকে পেস্ট করার অনুমতি দিন অথবা ম্যানুয়ালি পেস্ট করুন।');
        }
    }

    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.link-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
        updateBulkBar();
    }

    function updateBulkBar() {
        const checkedCount = document.querySelectorAll('.link-checkbox:checked').length;
        const bulkBar = document.getElementById('bulkActionsBar');
        const countSpan = document.getElementById('selectedCount');
        const master = document.getElementById('selectAllCheckbox');
        
        countSpan.innerText = checkedCount;
        if (checkedCount > 0) {
            bulkBar.classList.remove('hidden');
        } else {
            bulkBar.classList.add('hidden');
            if (master) master.checked = false;
        }
    }

    function deleteSingleLink(id, status, phone) {
        let msg = 'আপনি কি নিশ্চিত এই লিংকটি ডাটাবেজ থেকে মুছে ফেলতে চান?';
        if (status === 'sold') {
            msg = `সতর্কবার্তা: এই লিংকটি বিক্রি হয়ে গেছে (${phone || 'Customer'})। আপনি কি নিশ্চিত এটি স্থায়ীভাবে মুছে ফেলতে চান?`;
        }
        if (confirm(msg)) {
            const form = document.getElementById('singleDeleteForm');
            form.action = `/admin/links/${id}`;
            form.submit();
        }
    }
</script>
@endpush
