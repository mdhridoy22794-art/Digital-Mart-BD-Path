@extends('admin.layout')

@section('page_title', 'অর্ডার তালিকা (Order Management)')
@section('page_subtitle', 'কাস্টমারদের সকল অর্ডার, ভেরিফাইড ট্রানজেকশন, লিংক ডেলিভারি ও রিকভারি সাপোর্ট')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- 1. Search & Filter Bar -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
        <form action="{{ route('admin.orders') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            
            <div class="relative flex-grow w-full">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-search"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="কাস্টমারের নাম, মোবাইল নম্বর, ইমেইল বা TrxID দিয়ে সার্চ করুন..." 
                       class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200/90 text-xs sm:text-sm text-slate-800 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition font-bn">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                <button type="submit" 
                        class="w-full sm:w-auto px-6 py-3 rounded-2xl gradient-brand text-white font-bold text-xs shadow-md shadow-purple-600/20 hover:opacity-95 transition flex items-center justify-center gap-2 font-bn">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    <span>সার্চ করুন</span>
                </button>

                @if($search)
                <a href="{{ route('admin.orders') }}" 
                   class="px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold font-bn transition flex items-center justify-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                    <span>রিসেট</span>
                </a>
                @endif
            </div>

        </form>
    </div>

    <!-- 2. Orders Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <form id="bulkDeleteForm" action="{{ route('admin.orders.bulk-delete') }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে নির্বাচিত অর্ডারগুলো মুছে ফেলতে চান?')">
            @csrf

            <!-- Header -->
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-brand-purple flex items-center justify-center">
                        <i class="fa-solid fa-boxes-packing text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-sm text-slate-900 font-bn">অর্ডার তালিকা ও রিকভারি</h3>
                        <p class="text-xs text-slate-400 font-bn">
                            @if($search)
                            "<span class="text-brand-600 font-semibold font-mono">{{ $search }}</span>" এর জন্য পাওয়া গেছে {{ $orders->total() }}টি ফলাফল
                            @else
                            মোট অর্ডার: <span class="text-slate-800 font-bold font-mono">{{ $orders->total() }}টি</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Bulk Delete Button -->
                    <button type="submit" id="bulkDeleteBtn" disabled
                            class="px-4 py-2 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 text-xs font-bold hover:bg-rose-600 hover:text-white transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5 font-bn">
                        <i class="fa-regular fa-trash-can"></i>
                        <span id="bulkDeleteBtnText">সিলেক্টেড অর্ডার মুছুন</span>
                    </button>

                    <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200 flex items-center gap-1.5 font-bn">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>রিয়েল-টাইম ক্লাউড সিঙ্ক</span>
                    </span>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                        <tr>
                            <th class="py-4 px-4 w-10 text-center">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" 
                                       class="rounded border-slate-300 text-brand-purple focus:ring-purple-500 w-4 h-4 cursor-pointer">
                            </th>
                            <th class="py-4 px-4 font-bn">অর্ডার নম্বর</th>
                            <th class="py-4 px-6 font-bn">কাস্টমার প্রোফাইল</th>
                            <th class="py-4 px-6 font-bn">পেমেন্ট মেথড ও প্রেরক</th>
                            <th class="py-4 px-6 font-bn">TrxID নম্বর</th>
                            <th class="py-4 px-6 font-bn">পরিশোধিত টাকা</th>
                            <th class="py-4 px-6 font-bn">ডেলিভারিকৃত লিংক (রিকভারি)</th>
                            <th class="py-4 px-6 font-bn">তারিখ ও সময়</th>
                            <th class="py-4 px-6 text-center font-bn">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            
                            <!-- Checkbox -->
                            <td class="py-4 px-4 text-center">
                                <input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" onchange="updateBulkDeleteBtn()"
                                       class="order-checkbox rounded border-slate-300 text-brand-purple focus:ring-purple-500 w-4 h-4 cursor-pointer">
                            </td>

                            <!-- Order Number -->
                            <td class="py-4 px-4">
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
                                        <span class="text-slate-500 font-mono text-[11px] flex items-center gap-1">
                                            <i class="fa-solid fa-phone text-[9px] text-slate-400"></i>
                                            {{ $order->customer_phone }}
                                        </span>
                                        @if(!empty($order->customer_email) && !str_contains($order->customer_email, '@digitalmart.com'))
                                        <span class="text-slate-400 font-mono text-[10px] block">
                                            {{ $order->customer_email }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Payment Method & Sender Phone -->
                            <td class="py-4 px-6">
                                @if(strtolower($order->payment_method) === 'zinipay')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                                    <span>⚡ ZiniPay Auto</span>
                                </span>
                                @elseif(strtolower($order->payment_method) === 'bkash')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-pink-700 border border-pink-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                                    <span>bKash Personal</span>
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                    <span>Nagad Personal</span>
                                </span>
                                @endif

                                @if($order->sender_phone)
                                <span class="text-slate-400 font-mono text-[10px] block mt-1">
                                    প্রেরক: {{ $order->sender_phone }}
                                </span>
                                @endif
                            </td>

                            <!-- TrxID -->
                            <td class="py-4 px-6">
                                @if($order->trx_id)
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono font-bold text-slate-800 bg-slate-100/80 px-2 py-1 rounded border border-slate-200 text-[11px] select-all">
                                        {{ $order->trx_id }}
                                    </span>
                                    <button type="button" onclick="copyText('{{ $order->trx_id }}', this)" 
                                            class="p-1 text-slate-400 hover:text-brand-600 rounded hover:bg-slate-100 transition" 
                                            title="Copy TrxID">
                                        <i class="fa-regular fa-copy text-xs"></i>
                                    </button>
                                </div>
                                @else
                                <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            <!-- Amount & Quantity -->
                            <td class="py-4 px-6">
                                <span class="font-mono font-extrabold text-slate-900 text-sm">
                                    ৳{{ number_format($order->amount, 0) }}
                                </span>
                                <span class="block text-[10px] text-purple-700 font-bold font-bn">
                                    পরিমাণ: {{ $order->quantity ?? 1 }}টি লিংক
                                </span>
                            </td>

                            <!-- Delivered Link (Recovery Support) -->
                            <td class="py-4 px-6 max-w-xs">
                                @php
                                    $deliveredList = array_values(array_filter(preg_split('/\r\n|\r|\n/', (string) $order->delivered_link)));
                                @endphp
                                @if(count($deliveredList) > 1)
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-full bg-purple-100 text-brand-700 text-[10px] font-bold font-mono">
                                            {{ count($deliveredList) }}টি লিংক
                                        </span>
                                        <button type="button" onclick="copyText('{{ addslashes(implode("\n", $deliveredList)) }}', this)" 
                                                class="px-2 py-0.5 rounded bg-purple-50 text-brand-600 hover:bg-brand-600 hover:text-white border border-purple-200 text-[10px] font-bold font-mono transition shrink-0"
                                                title="সব লিংক একসাথে কপি">
                                            Copy All
                                        </button>
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-mono truncate max-w-[170px]" title="{{ $deliveredList[0] }}">
                                        1. {{ $deliveredList[0] }}
                                    </div>
                                </div>
                                @elseif(count($deliveredList) === 1)
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ $deliveredList[0] }}" target="_blank" 
                                       class="font-mono text-[11px] text-brand-600 hover:underline truncate max-w-[150px] block" 
                                       title="{{ $deliveredList[0] }}">
                                        {{ $deliveredList[0] }}
                                    </a>
                                    <button type="button" onclick="copyText('{{ addslashes($deliveredList[0]) }}', this)" 
                                            class="px-2 py-0.5 rounded bg-purple-50 text-brand-600 hover:bg-brand-600 hover:text-white border border-purple-200 text-[10px] font-bold font-mono transition shrink-0">
                                        Copy
                                    </button>
                                </div>
                                @else
                                <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            <!-- Date Time -->
                            <td class="py-4 px-6 text-slate-400 font-mono text-[11px] whitespace-nowrap">
                                <span class="text-slate-800 font-semibold">{{ $order->created_at ? $order->created_at->timezone('Asia/Dhaka')->format('d M, Y') : '—' }}</span><br>
                                <span>{{ $order->created_at ? $order->created_at->timezone('Asia/Dhaka')->format('h:i A') : '—' }}</span>
                            </td>

                            <!-- Action & WhatsApp Support -->
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '88' . $cleanPhone;
                                        }
                                        $deliveredMsg = !empty($order->delivered_link) ? "\n\nআপনার অ্যাক্টিভেশন লিংক:\n" . $order->delivered_link : '';
                                        $waMessage = urlencode("আসসালামু আলাইকুম {$order->customer_name}, Digital Mart BD থেকে যোগাযোগ করছি আপনার অর্ডার #{$order->order_number} সম্পর্কে।{$deliveredMsg}\n\nধন্যবাদ!");
                                    @endphp

                                    <!-- WhatsApp Direct Button with prefilled link -->
                                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMessage }}" target="_blank"
                                       class="w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-200 hover:border-emerald-600 transition flex items-center justify-center shadow-sm"
                                       title="হোয়াটসঅ্যাপে লিংক পাঠান">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                    </a>

                                    <!-- Single Delete Order Button -->
                                    <button type="button" onclick="deleteSingleOrder({{ $order->id }}, '{{ $order->order_number }}')"
                                            class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 hover:border-rose-600 transition flex items-center justify-center shadow-sm"
                                            title="এই অর্ডারটি মুছে ফেলুন">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-12 px-6 text-center text-slate-400 font-bn">
                                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <span class="text-sm font-semibold block text-slate-600">কোনো অর্ডার পাওয়া যায়নি</span>
                                <span class="text-xs text-slate-400">অনুগ্রহ করে সঠিক নাম, মোবাইল নম্বর বা TrxID লিখে পুনরায় চেষ্টা করুন।</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                {{ $orders->links() }}
            </div>
            @endif

        </form>

    </div>

</div>

<!-- Hidden Form for Single Order Delete -->
<form id="singleDeleteOrderForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-check text-emerald-500"></i> Done`;
            setTimeout(() => {
                btn.innerHTML = originalHtml;
            }, 2000);
        });
    }

    function toggleSelectAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        updateBulkDeleteBtn();
    }

    function updateBulkDeleteBtn() {
        const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
        const btn = document.getElementById('bulkDeleteBtn');
        const btnText = document.getElementById('bulkDeleteBtnText');

        if (checkedCount > 0) {
            btn.disabled = false;
            btnText.innerText = `নির্বাচিত (${checkedCount})টি অর্ডার মুছুন`;
        } else {
            btn.disabled = true;
            btnText.innerText = 'সিলেক্টেড অর্ডার মুছুন';
            const master = document.getElementById('selectAllCheckbox');
            if (master) master.checked = false;
        }
    }

    function deleteSingleOrder(id, orderNumber) {
        if (confirm(`আপনি কি নিশ্চিত যে অর্ডার #${orderNumber} স্থায়ীভাবে মুছে ফেলতে চান?`)) {
            const form = document.getElementById('singleDeleteOrderForm');
            form.action = `/admin/orders/${id}`;
            form.submit();
        }
    }
</script>
@endsection
