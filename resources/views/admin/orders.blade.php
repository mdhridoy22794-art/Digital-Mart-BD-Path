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

                    <!-- ZiniPay Bulk Sync Button -->
                    <button type="button" onclick="submitSyncPending()" 
                            class="px-3.5 py-2 rounded-xl bg-purple-50 text-brand-purple border border-purple-200 text-xs font-bold hover:bg-brand-purple hover:text-white transition flex items-center gap-1.5 font-bn shadow-sm"
                            title="ZiniPay ক্লাউড থেকে সকল পেন্ডিং অর্ডার স্বয়ংক্রিয়ভাবে সিঙ্ক করুন">
                        <i class="fa-solid fa-rotate text-xs"></i>
                        <span>ZiniPay সিঙ্ক</span>
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

                            <!-- Order Number & Status -->
                            <td class="py-4 px-4">
                                <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                    #{{ $order->order_number }}
                                </span>
                                @php
                                    $stBadge = $order->status_badge;
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold {{ $stBadge['bg'] }} {{ $stBadge['text'] }} border {{ $stBadge['border'] }} mt-1.5 block w-fit">
                                    <span class="w-1 h-1 rounded-full {{ $stBadge['dot'] }}"></span>
                                    <span>{{ $stBadge['label'] }}</span>
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
                                @php
                                    $badge = $order->payment_method_badge;
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $badge['bg'] }} {{ $badge['text'] }} border {{ $badge['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                    <span>{{ $badge['name'] }}</span>
                                </span>

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
                                    @if($order->status !== 'completed')
                                    <!-- Verify & Confirm Button -->
                                    <button type="button" 
                                            onclick="openVerifyModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ addslashes($order->customer_name) }}', '{{ $order->customer_phone }}', '{{ number_format($order->amount, 0) }}', '{{ $order->invoice_id }}', '{{ $order->val_id }}')"
                                            class="px-2.5 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] font-bn flex items-center gap-1 shadow-sm transition shrink-0"
                                            title="পেমেন্ট ভেরিফাই ও কনফার্ম করুন">
                                        <i class="fa-solid fa-shield-check text-xs"></i>
                                        <span>ভেরিফাই</span>
                                    </button>
                                    @endif

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

<!-- Hidden Form for ZiniPay Bulk Sync -->
<form id="syncPendingOrdersForm" action="{{ route('admin.orders.sync-pending') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- Verify & Confirm Modal -->
<div id="verifyOrderModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-5">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-brand-purple flex items-center justify-center">
                    <i class="fa-solid fa-shield-check text-base"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900 font-bn">পেমেন্ট ভেরিফাই ও কনফার্ম</h4>
                    <span id="modalOrderNumber" class="text-xs font-mono font-bold text-brand-purple">#DM-XXXXXX</span>
                </div>
            </div>
            <button type="button" onclick="closeVerifyModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-slate-50 rounded-2xl p-3.5 border border-slate-200/80 space-y-1.5 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-500 font-bn">কাস্টমার নাম:</span>
                <span id="modalCustomerName" class="font-bold text-slate-800 font-bn">—</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 font-bn">মোবাইল নম্বর:</span>
                <span id="modalCustomerPhone" class="font-bold text-slate-800 font-mono">—</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 font-bn">প্রদেয় টাকা:</span>
                <span id="modalOrderAmount" class="font-extrabold text-brand-purple font-mono">৳—</span>
            </div>
            <div id="modalIdentifierBox" class="pt-1 text-[10px] text-slate-400 font-mono truncate hidden">
                ID: <span id="modalIdentifierText"></span>
            </div>
        </div>

        <!-- Option 1: Auto Verify with ZiniPay -->
        <form id="zinipayVerifyForm" action="" method="POST" class="space-y-2">
            @csrf
            <input type="hidden" name="action_type" value="zinipay">
            <button type="submit" id="zinipaySubmitBtn"
                    class="w-full py-3 rounded-2xl gradient-brand text-white font-bold text-xs shadow-md shadow-purple-600/20 hover:opacity-95 transition flex items-center justify-center gap-2 font-bn">
                <i class="fa-solid fa-bolt text-amber-300"></i>
                <span>ZiniPay থেকে অটো-ভেরিফাই করুন</span>
            </button>
            <p class="text-[10px] text-center text-slate-400 font-bn">
                ZiniPay চেক করে আসল TrxID আনবে ও অটোমেটিক লিংক বরাদ্দ করবে
            </p>
        </form>

        <div class="relative flex items-center justify-center my-2">
            <div class="border-t border-slate-200 w-full"></div>
            <span class="bg-white px-3 text-[11px] text-slate-400 font-bn uppercase tracking-wider absolute">অথবা</span>
        </div>

        <!-- Option 2: Manual Confirm by Admin -->
        <form id="manualVerifyForm" action="" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="action_type" value="manual">
            <div>
                <label class="block text-xs font-bold text-slate-700 font-bn mb-1">
                    বিকাশ/নগদ TrxID (ঐচ্ছিক):
                </label>
                <input type="text" name="trx_id" placeholder="যেমন: DJE8W2L238 (না থাকলে খালি রাখুন)" 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-brand-500 outline-none transition">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 font-bn mb-1">মেথড:</label>
                    <select name="payment_method" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bn text-slate-800 outline-none">
                        <option value="bKash">bKash</option>
                        <option value="Nagad">Nagad</option>
                        <option value="Rocket">Rocket</option>
                        <option value="manual">Manual / Cash</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 font-bn">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>ম্যানুয়াল কনফার্ম</span>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

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

    function openVerifyModal(id, orderNumber, customerName, customerPhone, amount, invoiceId, valId) {
        document.getElementById('modalOrderNumber').innerText = '#' + orderNumber;
        document.getElementById('modalCustomerName').innerText = customerName;
        document.getElementById('modalCustomerPhone').innerText = customerPhone;
        document.getElementById('modalOrderAmount').innerText = '৳' + amount;
        
        const idBox = document.getElementById('modalIdentifierBox');
        const idText = document.getElementById('modalIdentifierText');
        const identifier = valId || invoiceId;
        if (identifier) {
            idText.innerText = identifier;
            idBox.classList.remove('hidden');
        } else {
            idBox.classList.add('hidden');
        }

        document.getElementById('zinipayVerifyForm').action = `/admin/orders/${id}/verify`;
        document.getElementById('manualVerifyForm').action = `/admin/orders/${id}/verify`;

        document.getElementById('verifyOrderModal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verifyOrderModal').classList.add('hidden');
    }

    function submitSyncPending() {
        if (confirm('ZiniPay থেকে সকল পেন্ডিং অর্ডার স্বয়ংক্রিয়ভাবে সিঙ্ক ও ভেরিফাই করতে চান?')) {
            document.getElementById('syncPendingOrdersForm').submit();
        }
    }
</script>
@endsection
