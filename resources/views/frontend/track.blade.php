@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 md:py-14">

    <!-- Header Section -->
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-purple-100 text-brand-purple flex items-center justify-center text-2xl mx-auto mb-3 shadow-sm">
            <i class="fa-solid fa-magnifying-glass-location"></i>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight font-bn">অর্ডার ও ডেলিভারি ট্র্যাক করুন</h1>
        <p class="text-xs md:text-sm text-slate-500 mt-1 font-bn">আপনার ব্যবহৃত মোবাইল নম্বর বা ট্রানজেকশন আইডি (TrxID) দিয়ে সার্চ করুন</p>
    </div>

    <!-- Search Form -->
    <div class="glass-card p-6 rounded-2xl border border-slate-200 shadow-md mb-8">
        <form action="{{ route('order.track') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-search"></i>
                </div>
                <input type="text" name="query" value="{{ $query }}" required
                       placeholder="মোবাইল নম্বর (উদাঃ 017xxxxxxxx) বা TrxID লিখুন" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 text-sm focus:border-brand-purple focus:ring-1 focus:ring-brand-purple outline-none font-bn">
            </div>
            <button type="submit" class="py-3 px-6 rounded-xl text-white font-bold text-sm gradient-brand gradient-brand-hover shadow-md shadow-purple-500/20 flex items-center justify-center gap-2 transition shrink-0 font-bn">
                <span>খুঁজুন (Search)</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>
    </div>

    <!-- Search Results -->
    @if($query)
        @if($orders->count() > 0)
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-slate-700 font-bn">খুঁজে পাওয়া অর্ডারসমূহ ({{ $orders->count() }}টি):</h2>

            @foreach($orders as $order)
            @php
                $deliveredList = array_values(array_filter(preg_split('/\r\n|\r|\n/', (string) $order->delivered_link)));
                $totalQty = $order->quantity ?: count($deliveredList) ?: 1;
            @endphp
            <div class="glass-card p-5 md:p-6 rounded-3xl border border-purple-100 shadow-sm space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-xs text-slate-400 font-bn">অর্ডার নম্বর:</span>
                        <span class="font-bold font-en text-slate-900 text-sm">#{{ $order->order_number }}</span>
                    </div>
                    <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-3 py-1 rounded-full font-bn flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>ডেলিভারি সম্পন্ন (Completed)</span>
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block font-bn">কাস্টমার নাম:</span>
                        <span class="font-semibold text-slate-800 font-bn">{{ $order->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-bn">মোবাইল নম্বর:</span>
                        <span class="font-semibold text-slate-800 font-en">{{ $order->customer_phone }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-bn">অর্ডার পরিমাণ:</span>
                        <span class="font-bold text-brand-purple font-en text-sm">{{ $totalQty }}টি লিংক</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-bn">পরিশোধিত মূল্য:</span>
                        <span class="font-bold text-slate-900 font-en text-sm">৳{{ number_format($order->amount, 0) }}</span>
                    </div>
                </div>

                <!-- Delivered Links Container -->
                @if(count($deliveredList) > 0)
                <div class="p-4 rounded-2xl bg-slate-900 text-white space-y-3 mt-2 border border-slate-800">
                    <div class="flex items-center justify-between text-xs text-emerald-400 font-semibold">
                        <span class="font-bn flex items-center gap-1.5">
                            <i class="fa-solid fa-gift text-brand-purple"></i>
                            <span>আপনার ডেলিভারিকৃত লিংকসমূহ (সিরিয়াল অনুযায়ী):</span>
                        </span>
                        
                        @if(count($deliveredList) > 1)
                        <button type="button" 
                                onclick="navigator.clipboard.writeText(`{{ implode('\n', $deliveredList) }}`).then(() => alert('সবগুলো লিংক ক্লিপবোর্ডে কপি করা হয়েছে!'));"
                                class="px-2.5 py-1 rounded-lg bg-brand-purple hover:bg-purple-700 text-white text-[11px] font-bold font-bn transition shadow-sm">
                            <i class="fa-regular fa-copy"></i>
                            <span>সব লিংক একসাথে কপি</span>
                        </button>
                        @endif
                    </div>

                    <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                        @foreach($deliveredList as $idx => $linkUrl)
                        <div class="p-3 rounded-xl bg-slate-950 border border-slate-800/80 space-y-1.5 text-left">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-mono text-purple-300 font-bold">#{{ $idx + 1 }}</span>
                                <a href="{{ $linkUrl }}" target="_blank" class="text-purple-400 hover:text-white flex items-center gap-1 font-bn">
                                    <span>ব্রাউজারে খুলুন</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>
                            <div class="font-mono text-xs text-purple-200 break-all select-all py-1">
                                {{ $linkUrl }}
                            </div>
                            <button type="button" 
                                    onclick="navigator.clipboard.writeText('{{ $linkUrl }}').then(() => alert('লিংক #{{ $idx + 1 }} কপি করা হয়েছে!'));"
                                    class="w-full py-1.5 px-3 rounded-lg bg-purple-900/40 hover:bg-brand-purple text-purple-200 hover:text-white text-[11px] font-semibold flex items-center justify-center gap-1.5 transition">
                                <i class="fa-regular fa-copy"></i>
                                <span>এই লিংকটি কপি করুন</span>
                            </button>
                        </div>
                        @endforeach
                    </div>

                    @if(!empty($settings['tutorial_video_url']))
                    <div class="pt-2">
                        <button type="button" 
                                onclick="openTutorialVideoModal()" 
                                class="w-full group p-3 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-purple-700 hover:from-red-500 hover:to-purple-600 text-white shadow-md shadow-rose-500/20 transition-all flex items-center justify-between gap-3 text-left">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                                    <i class="fa-solid fa-play text-white text-xs ml-0.5 animate-pulse"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white font-bn block">
                                        {{ $settings['tutorial_video_btn_text'] ?? 'ভিডিও দেখুন: ১ মিনিটে চালু করার নিয়ম' }}
                                    </span>
                                    <span class="text-[10px] text-rose-100 font-bn">
                                        সহজে চালু করতে ১ মিনিটের ভিডিওটি দেখে নিন
                                    </span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs text-white/80 pr-1"></i>
                        </button>
                    </div>
                    @endif

                    <!-- Canva & CapCut Notice in Track -->
                    <div class="mt-3 p-3 rounded-xl bg-purple-950/70 border border-purple-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-left">
                        <div class="flex items-start gap-2 min-w-0">
                            <i class="fa-brands fa-whatsapp text-emerald-400 text-sm mt-0.5 shrink-0"></i>
                            <div>
                                <p class="text-xs font-bold text-purple-200 font-bn">
                                    ক্যানভা (Canva Pro) ও ক্যাপকাট (CapCut Pro) গ্রাহকদের জন্য:
                                </p>
                                <p class="text-[11px] text-purple-300/80 font-bn leading-normal mt-0.5">
                                    এক্টিভেশনের জন্য অনুগ্রহ করে আমাদের অফিসিয়াল হোয়াটসঅ্যাপে অর্ডার নম্বর পাঠিয়ে মেসেজ দিন।
                                </p>
                            </div>
                        </div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '8801934779775') }}?text={{ urlencode('হ্যালো! আমি ক্যানভা/ক্যাপকাট এক্টিভেশনের জন্য মেসেজ দিচ্ছি। আমার অর্ডার #' . ($order->order_number ?? '')) }}" 
                           target="_blank" 
                           class="shrink-0 self-start sm:self-center px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] flex items-center gap-1.5 font-bn shadow-sm transition">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                            <span>মেসেজ দিন</span>
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 glass-card rounded-2xl border border-slate-200">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                <i class="fa-solid fa-file-circle-question"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800 font-bn">কোনো অর্ডার পাওয়া যায়নি!</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto font-bn">
                "{{ $query }}" নম্বর বা TrxID দিয়ে কোনো অর্ডার রেকর্ড নেই। অনুগ্রহ করে নম্বরটি সঠিকভাবে দেখে পুনরায় চেষ্টা করুন।
            </p>
        </div>
        @endif
    @endif

    @if(!empty($settings['tutorial_video_url']))
    <!-- Tutorial Video Modal -->
    <div id="tutorialVideoModal" 
         class="fixed inset-0 z-50 hidden bg-slate-950/85 backdrop-blur-md p-3 sm:p-6 flex items-center justify-center transition-opacity duration-300 opacity-0"
         onclick="handleModalBackdropClick(event)">
        <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-700/80 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh]"
             onclick="event.stopPropagation()">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 sm:px-6 py-3.5 border-b border-slate-800 bg-slate-900/90">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-sm border border-rose-500/30">
                        <i class="fa-solid fa-play ml-0.5"></i>
                    </span>
                    <div>
                        <h3 class="text-xs sm:text-sm font-extrabold text-white font-bn">
                            {{ $settings['tutorial_video_btn_text'] ?? 'ভিডিও গাইড: কীভাবে চালু করবেন' }}
                        </h3>
                        <p class="text-[10px] text-slate-400 font-bn">ভিডিও দেখা শেষে ক্লোজ (✖) করে নিচে লিংক কপি করুন</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="closeTutorialVideoModal(true)" 
                        aria-label="Close"
                        class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-300 hover:text-white flex items-center justify-center transition text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Video Player Box -->
            <div class="p-2 sm:p-4 bg-black">
                <div id="videoContainer" class="relative w-full aspect-video rounded-2xl overflow-hidden bg-black shadow-2xl" style="position: relative; width: 100%; aspect-ratio: 16 / 9; min-height: 240px; background-color: #000000;">
                    <!-- Injected dynamically on open -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 sm:px-6 py-3 border-t border-slate-800 bg-slate-900/95 flex items-center justify-between gap-3">
                <span class="text-[11px] text-slate-400 font-bn flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>ভিডিও দেখে নিচের লিংকটি ব্রাউজারে চালু করুন</span>
                </span>
                <button type="button" 
                        onclick="closeTutorialVideoModal(true)" 
                        class="px-4 py-2 rounded-xl bg-brand-purple hover:bg-purple-600 text-white font-bold text-xs flex items-center gap-1.5 transition font-bn shadow-md shadow-purple-500/20">
                    <i class="fa-regular fa-copy"></i>
                    <span>লিংক কপি করতে ফেরত যান</span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    @if(!empty($settings['tutorial_video_url']))
    const rawTutorialVideo = @json($settings['tutorial_video_url']);

    function parseTutorialVideo(raw) {
        if (!raw) return '';
        const trimmed = raw.trim();

        // 1. Raw iframe code provided
        if (trimmed.includes('<iframe')) {
            let clean = trimmed
                .replace(/width="[^"]*"/gi, '')
                .replace(/height="[^"]*"/gi, '')
                .replace(/style="[^"]*"/gi, '');
            return clean.replace(
                /<iframe/i, 
                '<iframe class="absolute inset-0 w-full h-full border-0" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen'
            );
        }

        // 2. YouTube URL (watch?v=, youtu.be/, shorts/, embed/)
        const ytMatch = trimmed.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
        if (ytMatch && ytMatch[1]) {
            const videoId = ytMatch[1];
            return `<iframe class="absolute inset-0 w-full h-full border-0" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" src="https://www.youtube.com/embed/${videoId}?autoplay=1&playsinline=1&rel=0&enablejsapi=1" title="Tutorial Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
        }

        // 3. Google Drive preview support
        const gdMatch = trimmed.match(/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/i);
        if (gdMatch && gdMatch[1]) {
            const fileId = gdMatch[1];
            return `<iframe class="absolute inset-0 w-full h-full border-0" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" src="https://drive.google.com/file/d/${fileId}/preview" allow="autoplay; fullscreen" allowfullscreen></iframe>`;
        }

        // 4. Direct HTML5 video (.mp4, .webm)
        if (/\.(mp4|webm|ogg)($|\?)/i.test(trimmed)) {
            return `<video src="${trimmed}" class="absolute inset-0 w-full h-full object-contain" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #000;" controls autoplay playsinline></video>`;
        }

        // 5. Generic iframe fallback
        return `<iframe class="absolute inset-0 w-full h-full border-0" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" src="${trimmed}" allow="autoplay; fullscreen" allowfullscreen></iframe>`;
    }

    function openTutorialVideoModal() {
        const modal = document.getElementById('tutorialVideoModal');
        const container = document.getElementById('videoContainer');
        if (!modal || !container) return;

        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
        });
        container.innerHTML = parseTutorialVideo(rawTutorialVideo);
        document.body.style.overflow = 'hidden';

        history.pushState({ tutorialVideoOpen: true }, '');
    }

    function closeTutorialVideoModal(shouldHistoryBack = false) {
        const modal = document.getElementById('tutorialVideoModal');
        const container = document.getElementById('videoContainer');
        if (!modal || modal.classList.contains('hidden')) return;

        modal.classList.add('opacity-0');
        if (container) container.innerHTML = '';

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);

        if (shouldHistoryBack && history.state && history.state.tutorialVideoOpen) {
            history.back();
        }
    }

    function handleModalBackdropClick(e) {
        if (e.target.id === 'tutorialVideoModal') {
            closeTutorialVideoModal(true);
        }
    }

    window.addEventListener('popstate', function(e) {
        const modal = document.getElementById('tutorialVideoModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeTutorialVideoModal(false);
        }
    });

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTutorialVideoModal(true);
        }
    });
    @endif
</script>
@endsection
