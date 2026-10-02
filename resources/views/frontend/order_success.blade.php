@extends('layouts.app')

@section('title', 'অর্ডার সফল হয়েছে - ' . $order->order_number . ' | Digital Mart BD')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 sm:py-12">
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden">
        
        <!-- Header Banner -->
        <div class="gradient-brand p-6 sm:p-8 text-white text-center relative overflow-hidden">
            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg border border-white/30 animate-bounce">
                <i class="fa-solid fa-circle-check text-emerald-300"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-bn">পেমেন্ট সফল ও লিংক ডেলিভারি!</h1>
            <p class="text-xs sm:text-sm text-purple-100 mt-1.5 font-bn">
                আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে। নিচে আপনার কাঙ্ক্ষিত সাবস্ক্রিপশন লিংক প্রদান করা হলো।
            </p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">

            <!-- Order Summary Card -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                <div class="p-2">
                    <span class="text-[11px] text-slate-500 block font-bn">অর্ডার নম্বর</span>
                    <strong class="text-xs sm:text-sm font-mono font-bold text-slate-800">{{ $order->order_number }}</strong>
                </div>
                <div class="p-2">
                    <span class="text-[11px] text-slate-500 block font-bn">মোট পরিমাণ</span>
                    <strong class="text-xs sm:text-sm font-mono font-bold text-purple-700">{{ $order->quantity ?? 1 }}টি লিংক</strong>
                </div>
                <div class="p-2">
                    <span class="text-[11px] text-slate-500 block font-bn">পরিশোধিত অর্থ</span>
                    <strong class="text-xs sm:text-sm font-mono font-bold text-emerald-600">৳{{ number_format($order->amount, 0) }}</strong>
                </div>
                <div class="p-2">
                    <span class="text-[11px] text-slate-500 block font-bn">পেমেন্ট মেথড</span>
                    <strong class="text-xs sm:text-sm font-mono font-bold uppercase text-slate-800">{{ $order->payment_method }}</strong>
                </div>
            </div>

            @if(!empty($order->trx_id))
            <div class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-purple-50/60 border border-purple-100 text-xs">
                <span class="text-slate-600 font-bn">ট্রানজেকশন আইডি (TrxID):</span>
                <span class="font-mono font-bold text-brand-purple">{{ $order->trx_id }}</span>
            </div>
            @endif

            <!-- Links Delivery Section -->
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-1 border-b border-slate-100">
                    <span class="text-sm font-extrabold text-slate-900 font-bn flex items-center gap-2">
                        <i class="fa-solid fa-gift text-brand-purple"></i>
                        <span>আপনার অ্যাক্টিভেশন লিংকসমূহ (ক্রমানুসারে):</span>
                    </span>
                    
                    @if(count($deliveredLinks) > 0)
                    <button type="button" onclick="copyAllLinks()" id="copyAllSuccessBtn"
                            class="px-3.5 py-1.5 rounded-xl gradient-brand text-white font-bold text-xs hover:opacity-95 transition flex items-center gap-1.5 shadow-sm">
                        <i class="fa-regular fa-copy"></i>
                        <span id="copyAllText">সব লিংক একসাথে কপি</span>
                    </button>
                    @endif
                </div>

                @if(count($deliveredLinks) > 0)
                    <div class="space-y-3">
                        @foreach($deliveredLinks as $item)
                        <div class="p-4 rounded-2xl bg-slate-900 text-left border border-slate-800 shadow-md space-y-2.5">
                            <div class="flex items-center justify-between text-xs text-emerald-400 font-semibold">
                                <span class="flex items-center gap-1.5 font-bn">
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono text-[11px] font-bold">#{{ $item['serial'] }}</span>
                                    <span>অ্যাক্টিভেশন লিংক #{{ $item['serial'] }}:</span>
                                </span>
                                <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" 
                                   class="text-[11px] text-purple-300 hover:text-white flex items-center gap-1 transition">
                                    <span>ওপেন করুন</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-950 font-mono text-xs text-purple-200 select-all break-all border border-slate-800/80">
                                {{ $item['url'] }}
                            </div>

                            <button type="button" onclick="copyText('{{ addslashes($item['url']) }}', this)" 
                                    class="w-full py-2.5 px-4 rounded-xl bg-purple-600/30 hover:bg-brand-purple text-purple-200 hover:text-white border border-purple-500/30 text-xs font-semibold flex items-center justify-center gap-2 transition">
                                <i class="fa-regular fa-copy"></i>
                                <span>এই লিংকটি কপি করুন</span>
                            </button>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bn">
                        <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                        লিংক প্রসেস হতে সামান্য কয়েক সেকেন্ড দেরি হতে পারে। পৃষ্ঠাটি রিফ্রেশ দিন অথবা হেল্পলাইনে যোগাযোগ করুন।
                    </div>
                @endif
            </div>

            @if(!empty($settings['tutorial_video_url']))
            <!-- Tutorial Video Trigger Button -->
            <button type="button" 
                    onclick="openTutorialVideoModal()" 
                    class="w-full group relative overflow-hidden p-3.5 sm:p-4 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-purple-700 hover:from-red-500 hover:to-purple-600 text-white shadow-lg shadow-rose-500/20 hover:shadow-rose-500/35 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-between gap-3 text-left">
                
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shrink-0 border border-white/30 group-hover:scale-110 transition duration-300 shadow-inner">
                        <i class="fa-solid fa-play text-white text-xs sm:text-sm ml-0.5 animate-pulse"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs sm:text-sm font-extrabold tracking-wide text-white font-bn drop-shadow-sm">
                                {{ $settings['tutorial_video_btn_text'] ?? 'ভিডিও দেখুন: ১ মিনিটে চালু করার নিয়ম' }}
                            </span>
                            <span class="px-1.5 py-0.5 rounded bg-amber-300 text-slate-950 font-bold text-[9px] uppercase tracking-wider font-sans">
                                Video Guide
                            </span>
                        </div>
                        <p class="text-[11px] sm:text-xs text-rose-100 font-bn truncate opacity-95">
                            সহজে বুঝতে এবং কোনো ভুল না করতে ১ মিনিটের ভিডিওটি দেখুন
                        </p>
                    </div>
                </div>

                <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-white/10 group-hover:bg-white/25 transition">
                    <i class="fa-solid fa-chevron-right text-xs text-white"></i>
                </div>
            </button>

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

            <!-- Activation Instructions Guide -->
            <div class="p-5 rounded-2xl bg-purple-50/70 border border-purple-200 text-xs text-slate-700 space-y-2">
                <h4 class="font-bold text-brand-purple flex items-center gap-2 text-sm font-bn">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>সার্ভিসটি কীভাবে চালু করবেন?</span>
                </h4>
                <ol class="list-decimal list-inside space-y-1.5 text-slate-600 text-xs font-bn leading-relaxed">
                    <li>উপরের লিংকের পাশের <strong>'কপি'</strong> বাটনে চাপ দিন (অথবা 'সব লিংক একসাথে কপি' করুন)।</li>
                    <li>আপনার ফোনের বা কম্পিউটারের <strong>Google Chrome</strong> ব্রাউজারে গিয়ে লিংকটি পেস্ট করে এন্টার দিন।</li>
                    <li>গুগল ওয়ান (Google One) পেজ লোড হলে নিচে <strong>"Activate plan"</strong> বাটনে ট্যাপ করুন।</li>
                    <li>ব্যাস! সাথে সাথেই আপনার জিমেইল অ্যাকাউন্টে জেমিনাই প্রো ও ৫টিবি স্টোরেজ সক্রিয় হয়ে যাবে।</li>
                </ol>

                <!-- Special notice for Canva & CapCut -->
                <div class="mt-4 pt-3.5 border-t border-purple-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white/90 p-3.5 rounded-xl border border-purple-100 shadow-sm">
                    <div class="flex items-start gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 sm:mt-0 text-base shadow-sm">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 font-bn">
                                ক্যানভা প্রো (Canva Pro) ও ক্যাপকাট (CapCut Pro) গ্রাহকদের জন্য:
                            </p>
                            <p class="text-[11px] text-slate-600 font-bn leading-normal mt-0.5">
                                আপনি যদি ক্যানভা বা ক্যাপকাট অর্ডার করে থাকেন, তবে দ্রুত সার্ভিস একটিভেশনের জন্য অনুগ্রহ করে আমাদের হোয়াটসঅ্যাপে মেসেজ দিন।
                            </p>
                        </div>
                    </div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '8801934779775') }}?text={{ urlencode('হ্যালো! আমি ক্যানভা/ক্যাপকাট এক্টিভেশনের জন্য মেসেজ দিচ্ছি। আমার অর্ডার #' . ($order->order_number ?? '')) }}" 
                       target="_blank" 
                       class="shrink-0 self-start sm:self-center px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 font-bn shadow-sm transition active:scale-95">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>হোয়াটসঅ্যাপে মেসেজ দিন</span>
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <a href="{{ route('order.track', ['query' => $order->order_number]) }}" 
                   class="w-full py-3 px-4 rounded-xl border border-purple-300 text-brand-purple hover:bg-purple-50 font-bold text-xs text-center flex items-center justify-center gap-2 transition font-bn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>অর্ডার ট্র্যাক / হিস্টোরি দেখুন</span>
                </a>

                <a href="{{ route('home') }}" 
                   class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs text-center flex items-center justify-center gap-2 transition font-bn">
                    <i class="fa-solid fa-house"></i>
                    <span>হোম পেজে ফিরে যান</span>
                </a>
            </div>

        </div>

    </div>
</div>

<textarea id="allLinksStorage" class="hidden">{{ $order->delivered_link }}</textarea>

@endsection

@section('scripts')
<script>
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-check text-emerald-400"></i><span class="text-emerald-400">কপি সম্পন্ন হয়েছে!</span>`;
            setTimeout(() => {
                btn.innerHTML = originalHtml;
            }, 2500);
        });
    }

    function copyAllLinks() {
        const text = document.getElementById('allLinksStorage')?.value || '';
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => {
            const btnText = document.getElementById('copyAllText');
            btnText.innerText = 'সব লিংক কপি হয়েছে!';
            setTimeout(() => {
                btnText.innerText = 'সব লিংক একসাথে কপি';
            }, 2500);
        });
    }

    // Fire Facebook Pixel Purchase if loaded
    @if(isset($settings['meta_pixel_id']) && !empty($settings['meta_pixel_id']))
    if (typeof fbq === 'function') {
        fbq('track', 'Purchase', {
            content_name: "{{ $order->product->name ?? 'Digital Subscription' }}",
            value: {{ (float) $order->amount }},
            currency: 'BDT',
            order_id: "{{ $order->order_number }}"
        });
    }
    @endif

    // Tutorial Video Modal Management
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

        // Push history state so mobile physical back button closes modal smoothly
        history.pushState({ tutorialVideoOpen: true }, '');
    }

    function closeTutorialVideoModal(shouldHistoryBack = false) {
        const modal = document.getElementById('tutorialVideoModal');
        const container = document.getElementById('videoContainer');
        if (!modal || modal.classList.contains('hidden')) return;

        modal.classList.add('opacity-0');
        if (container) container.innerHTML = ''; // Stop video & audio playback instantly

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

    // Hardware back button / browser navigation listener
    window.addEventListener('popstate', function(e) {
        const modal = document.getElementById('tutorialVideoModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeTutorialVideoModal(false); // don't push another history state
        }
    });

    // Escape key listener for desktop users
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTutorialVideoModal(true);
        }
    });
    @endif
</script>
@endsection
