<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settings['site_title'] ?? 'Digital Mart BD - সর্ববৃহৎ ডিজিটাল সাবস্ক্রিপশন ও সফটওয়্যার স্টোর' }}</title>
    <meta name="description" content="গুগল জেমিনাই প্রো ১৮ মাস সহ সকল প্রিমিয়াম সফটওয়্যার ও ডিজিটাল সাবস্ক্রিপশন। ১ সেকেন্ডে ইনস্ট্যান্ট লিংক ডেলিভারি!">
    <meta name="keywords" content="gemini pro bd, gemini ai pro 18 months, digital mart bd, canva pro bangladesh, digital shop bd">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $settings['site_title'] ?? 'Digital Mart BD' }}">
    <meta property="og:description" content="গুগল জেমিনাই প্রো ১৮ মাস মাত্র ২৫০ টাকায়! ১ সেকেন্ডে ইনস্ট্যান্ট ডেলিভারি।">
    <meta property="og:image" content="{{ asset('images/logo.jpg') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">

    <!-- Google Fonts: Hind Siliguri for Bengali & Inter for English -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Hind Siliguri"', 'Inter', 'sans-serif'],
                        en: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            purple: '#7C3AED',
                            violet: '#6A11CB',
                            pink: '#EC4899',
                            dark: '#0B0F19',
                            surface: '#151C2C',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }
        .gradient-brand {
            background: linear-gradient(135deg, #6A11CB 0%, #EC4899 100%);
        }
        .gradient-brand-hover:hover {
            background: linear-gradient(135deg, #5B0EAF 0%, #D81B60 100%);
            box-shadow: 0 10px 25px -5px rgba(106, 17, 203, 0.4), 0 8px 10px -6px rgba(236, 72, 153, 0.4);
        }
        .gradient-outline {
            position: relative;
            background: #fff;
            border-radius: 0.85rem;
            z-index: 1;
        }
        .gradient-outline::before {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: 0.95rem;
            padding: 2px;
            background: linear-gradient(135deg, #6A11CB, #EC4899);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        .gradient-outline:hover::before {
            inset: -3px;
            filter: drop-shadow(0 0 8px rgba(124, 58, 237, 0.6));
        }
        .gradient-text {
            background: linear-gradient(135deg, #6A11CB 0%, #EC4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .glass-card-dark {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        @keyframes shine {
            0% { left: -100%; }
            100% { left: 200%; }
        }
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -100%;
            width: 50%;
            height: 200%;
            background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0) 100%);
            transform: rotate(30deg);
            animation: shine 4s infinite ease-in-out;
        }
        .scale-hover {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
        }
        .scale-hover:hover {
            transform: translateY(-4px) scale(1.02);
        }
    </style>

    <!-- Meta Pixel Code (Supports single or multiple Pixel IDs separated by commas or newlines) -->
    @php
        $rawPixelIds = $settings['meta_pixel_id'] ?? '';
        $pixelIds = array_values(array_filter(array_map('trim', preg_split('/[,;\r\n]+/', (string)$rawPixelIds)), function($id) {
            return preg_match('/^\d+$/', $id);
        }));
    @endphp
    @if(count($pixelIds) > 0)
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        @foreach($pixelIds as $pid)
        fbq('init', '{{ $pid }}');
        @endforeach
        fbq('track', 'PageView');
    </script>
    <noscript>
        @foreach($pixelIds as $pid)
        <img height="1" width="1" style="display:none" 
             src="https://www.facebook.com/tr?id={{ $pid }}&ev=PageView&noscript=1"/>
        @endforeach
    </noscript>
    @endif

    @if(!empty($settings['custom_header_script']))
        @php
            $rawHeaderScript = trim((string)$settings['custom_header_script']);
        @endphp
        @if(str_contains($rawHeaderScript, '<'))
            {!! $rawHeaderScript !!}
        @endif
    @endif
</head>
<body class="min-h-screen flex flex-col antialiased bg-slate-50 text-slate-800">

    <!-- Sleek Top Announcement Bar -->
    @if(!empty($settings['announcement_text']))
    <div id="topAnnouncementBar" class="gradient-brand text-white py-1 px-3 sm:px-4 text-center text-[10px] sm:text-xs font-medium tracking-wide flex items-center justify-center gap-1.5 shadow-sm relative transition-all">
        <i class="fa-solid fa-fire text-yellow-300 text-xs shrink-0 animate-pulse"></i>
        <span class="truncate max-w-[80vw] sm:max-w-none">{{ $settings['announcement_text'] }}</span>
        <button type="button" onclick="document.getElementById('topAnnouncementBar').style.display='none'" class="absolute right-2 text-white/70 hover:text-white text-xs px-1" title="Close">
            <i class="fa-solid fa-xmark text-[10px]"></i>
        </button>
    </div>
    @endif

    <!-- Main Store Header -->
    <header class="bg-white border-b border-slate-200/90 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 py-2 sm:py-3.5">
            <!-- Row 1: Logo (Left) & Actions + Hamburger (Right) -->
            <div class="flex items-center justify-between gap-2">
                <!-- Brand Logo & Name -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 shrink-0 group">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Digital Mart BD Logo" class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl shadow-md border border-purple-200 object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="flex flex-col">
                        <span class="text-lg sm:text-2xl font-black font-en gradient-text tracking-tight leading-none">Digital Mart BD</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-500 font-semibold tracking-wider uppercase mt-0.5">ডিজিটাল সলিউশন ও স্টোর</span>
                    </div>
                </a>

                <!-- Desktop Search Bar (Hidden on Mobile) -->
                <div class="hidden md:block flex-grow max-w-lg mx-6">
                    <form action="{{ route('home') }}" method="GET" class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search for products... (যেমন: Gemini Pro, Canva, Tools)" 
                               class="w-full pl-4 pr-12 py-2 rounded-full border border-purple-200/80 bg-slate-50/60 text-xs focus:bg-white focus:border-brand-purple focus:ring-2 focus:ring-purple-100 outline-none transition duration-200">
                        <button type="submit" class="absolute right-1 w-7 h-7 rounded-full gradient-brand text-white flex items-center justify-center hover:opacity-90 transition shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </button>
                    </form>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                    <!-- WhatsApp Support Button (Mobile & Desktop) -->
                    @php
                        $activeWhatsApp = !empty($settings['whatsapp_number']) ? $settings['whatsapp_number'] : '+880 1934-779775';
                        $cleanWhatsApp = preg_replace('/[^0-9]/', '', $activeWhatsApp);
                        if (empty($cleanWhatsApp)) { $cleanWhatsApp = '8801934779775'; }
                    @endphp
                    <a href="https://wa.me/{{ $cleanWhatsApp }}" target="_blank" 
                       class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition text-emerald-800" title="WhatsApp Chat Support">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i>
                        <span class="hidden lg:inline text-[11px] font-bold font-en text-slate-900">{{ $activeWhatsApp }}</span>
                    </a>

                    <!-- Track Order Button (Desktop) -->
                    <a href="{{ route('order.track') }}" class="hidden sm:flex w-8 h-8 sm:w-9 sm:h-9 rounded-xl border border-slate-200 hover:border-purple-300 hover:bg-purple-50 items-center justify-center text-slate-700 hover:text-brand-purple transition" title="অর্ডার ট্র্যাক করুন">
                        <i class="fa-solid fa-truck-fast text-xs sm:text-sm"></i>
                    </a>

                    <!-- Cart Badge Button -->
                    <a href="{{ route('product.details', 'gemini-pro-18m') }}" class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl gradient-brand text-white shadow-sm shadow-purple-500/20 btn-shine transition transform hover:scale-105" title="কার্ট / অর্ডার">
                        <i class="fa-solid fa-cart-shopping text-xs"></i>
                        <span class="text-xs font-bold font-en">৳{{ number_format(\App\Models\Product::where('slug', 'gemini-pro-18m')->value('offer_price') ?? 250, 0) }}</span>
                    </a>

                    <!-- Mobile Drawer Menu Toggle Button (হাতের ডান দিকে) -->
                    <button type="button" onclick="toggleMobileDrawer(true)" 
                            class="md:hidden w-8 h-8 rounded-xl border border-purple-200 bg-purple-50 text-brand-purple hover:bg-brand-purple hover:text-white flex items-center justify-center transition shadow-sm ml-0.5" 
                            aria-label="মেনু ওপেন করুন" title="মেনু">
                        <i class="fa-solid fa-bars-staggered text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Row 2 (Mobile Only): Compact Search Bar -->
            <div class="md:hidden mt-2">
                <form action="{{ route('home') }}" method="GET" class="relative flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search products... (Gemini, Canva, Tools)" 
                           class="w-full pl-3.5 pr-10 py-1.5 rounded-full border border-purple-200/90 bg-slate-50 text-xs focus:bg-white focus:border-brand-purple focus:ring-2 focus:ring-purple-100 outline-none transition">
                    <button type="submit" class="absolute right-1 w-6 h-6 rounded-full gradient-brand text-white flex items-center justify-center hover:opacity-90 transition">
                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Desktop Navigation Bar (Hidden on Mobile) -->
        <div class="hidden md:block border-t border-slate-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 flex items-center justify-between text-xs font-semibold">
                <div class="flex items-center gap-1 sm:gap-2 py-2">
                    <!-- All Categories Dropdown Trigger -->
                    <div class="relative group">
                        <button class="px-3.5 py-1.5 rounded-lg gradient-brand text-white flex items-center gap-2 font-bold shadow-sm hover:opacity-95 transition">
                            <i class="fa-solid fa-bars"></i>
                            <span>All Categories</span>
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute left-0 top-full pt-1 hidden group-hover:block w-56 z-50">
                            <div class="bg-white rounded-xl shadow-xl border border-slate-200 p-2 space-y-1 text-slate-700 font-medium">
                                <a href="{{ route('product.details', 'gemini-pro-18m') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
                                    <i class="fa-solid fa-wand-magic-sparkles text-purple-600 text-xs"></i>
                                    <span>AI Tools (Gemini Pro)</span>
                                </a>
                                <a href="{{ route('home') }}#trending-section" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
                                    <i class="fa-solid fa-laptop-code text-blue-600 text-xs"></i>
                                    <span>Software & Utilities</span>
                                </a>
                                <a href="{{ route('product.details', 'canva-pro-1-year') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
                                    <i class="fa-solid fa-palette text-pink-600 text-xs"></i>
                                    <span>Canva & Graphic Design</span>
                                </a>
                                <a href="{{ route('home') }}#recent-section" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
                                    <i class="fa-solid fa-layer-group text-emerald-600 text-xs"></i>
                                    <span>Templates & Bundles</span>
                                </a>
                                <a href="{{ route('home') }}#recent-section" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
                                    <i class="fa-solid fa-thumbs-up text-indigo-600 text-xs"></i>
                                    <span>Likes & Social Media</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('home') ? 'bg-purple-100 text-brand-purple font-bold' : 'text-slate-600 hover:text-brand-purple hover:bg-slate-50' }} transition">
                        Home
                    </a>
                    <a href="{{ route('home') }}#trending-section" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition">
                        Shop
                    </a>
                    <a href="{{ route('product.details', 'gemini-pro-18m') }}" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition flex items-center gap-1">
                        <span>Gemini AI Pro</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500 animate-ping"></span>
                    </a>
                    <a href="{{ route('home') }}#trending-section" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition">
                        Software
                    </a>
                    <a href="{{ route('home') }}#recent-section" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition">
                        Templates
                    </a>
                    <a href="#footer-section" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition">
                        Contact Us
                    </a>
                </div>

                <div class="hidden lg:flex items-center gap-2 text-slate-500 text-[11px]">
                    <i class="fa-solid fa-bolt text-yellow-500"></i>
                    <span>ইনস্ট্যান্ট ১-সেকেন্ড স্বয়ংক্রিয় লিংক ডেলিভারি</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Body -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Mega E-commerce Footer -->
    <footer id="footer-section" class="bg-slate-950 text-slate-400 pt-16 pb-24 md:pb-12 border-t border-slate-800 text-xs">
        <div class="max-w-7xl mx-auto px-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 pb-12 border-b border-slate-800">
                <!-- Col 1: Brand Info -->
                <div class="space-y-4 flex flex-col items-center md:items-start text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-3">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-10 h-10 rounded-xl border border-purple-500/30">
                        <span class="text-white text-xl font-bold font-en tracking-tight">Digital Mart BD</span>
                    </div>
                    <p class="text-slate-400 leading-relaxed text-[11px] max-w-sm">
                        Digital Mart BD হলো আসল ও নির্ভরযোগ্য প্রিমিয়াম সফটওয়্যার, এআই টুলস, এবং ডিজিটাল সাবস্ক্রিপশনের ওয়ান-স্টপ মার্কেটপ্লেস। আমরা শতভাগ গ্যারান্টি সহকারে অটোমেটিক ইনস্ট্যান্ট অ্যাক্সেস প্রদান করি।
                    </p>
                    <div class="space-y-2 text-[11px] flex flex-col items-center md:items-start">
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-purple-400"></i>
                            <span class="text-white font-en">{{ $activeWhatsApp }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-pink-400"></i>
                            <span class="text-white">support@digitalmartbd.com</span>
                        </p>
                    </div>
                </div>

                <!-- Col 2: Customer Service -->
                <div class="flex flex-col items-center md:items-start text-center md:text-left">
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider font-en">Customer Service</h4>
                    <ul class="space-y-2.5 text-[11px] flex flex-col items-center md:items-start">
                        <li><a href="https://wa.me/{{ $cleanWhatsApp }}" target="_blank" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Contact Us</a></li>
                        <li><a href="{{ route('order.track') }}" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Track Order</a></li>
                        <li><a href="#how-to-buy" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> How to Buy</a></li>
                        <li><a href="#faq" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> FAQ</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Col 3: Useful Links -->
                <div class="flex flex-col items-center md:items-start text-center md:text-left">
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider font-en">Useful Links</h4>
                    <ul class="space-y-2.5 text-[11px] flex flex-col items-center md:items-start">
                        <li><a href="{{ route('home') }}" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> All Products</a></li>
                        <li><a href="{{ route('product.details', 'gemini-pro-18m') }}" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Gemini AI Pro 18M</a></li>
                        <li><a href="{{ route('home') }}#trending-section" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> AI Tools</a></li>
                        <li><a href="{{ route('home') }}#trending-section" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Software Licenses</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Admin Panel</a></li>
                    </ul>
                </div>

                <!-- Col 4: Follow Us & Payment Methods -->
                <div class="flex flex-col items-center md:items-start text-center md:text-left">
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider font-en">Follow Us</h4>
                    <p class="text-[11px] mb-3 text-slate-400">Stay connected with our official channels:</p>
                    <div class="flex items-center justify-center md:justify-start gap-2.5 mb-6">
                        @if(!empty($settings['facebook_url']))
                        <a href="{{ $settings['facebook_url'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-blue-600 hover:border-blue-600 text-white flex items-center justify-center transition shadow-sm" title="Facebook">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        @endif
                        <a href="https://wa.me/{{ $cleanWhatsApp }}" target="_blank" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-emerald-600 hover:border-emerald-600 text-white flex items-center justify-center transition shadow-sm" title="WhatsApp">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-cyan-500 hover:border-cyan-500 text-white flex items-center justify-center transition shadow-sm" title="Telegram">
                            <i class="fa-brands fa-telegram text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-red-600 hover:border-red-600 text-white flex items-center justify-center transition shadow-sm" title="YouTube">
                            <i class="fa-brands fa-youtube text-xs"></i>
                        </a>
                    </div>

                    <h5 class="text-white font-semibold text-xs mb-2.5">WE ACCEPT:</h5>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 text-[10px] font-bold">
                        <span class="bg-pink-950/60 border border-pink-700/50 text-pink-300 px-3 py-1 rounded-lg">bKash</span>
                        <span class="bg-orange-950/60 border border-orange-700/50 text-orange-300 px-3 py-1 rounded-lg">Nagad</span>
                        <span class="bg-purple-950/60 border border-purple-700/50 text-purple-300 px-3 py-1 rounded-lg">Rocket</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Subfooter -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 gap-3 text-center sm:text-left">
                <p>© {{ date('Y') }} Digital Mart BD. All rights reserved.</p>
                <p class="text-slate-600">Built with Laravel & Ultra-Speed Architecture</p>
            </div>

        </div>
    </footer>

    <!-- Floating WhatsApp Bubble -->
    <a href="https://wa.me/{{ $cleanWhatsApp }}" target="_blank"
       class="fixed bottom-6 right-6 z-40 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center shadow-xl shadow-emerald-500/40 transform hover:scale-110 transition duration-300"
       title="WhatsApp Chat">
        <i class="fa-brands fa-whatsapp text-2xl sm:text-3xl"></i>
    </a>

    <!-- Mobile Off-Canvas Drawer (Left Slide-in) -->
    <div id="mobileDrawerOverlay" onclick="toggleMobileDrawer(false)" 
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <div id="mobileDrawer" 
         class="fixed top-0 bottom-0 left-0 w-[82vw] max-w-xs bg-white z-50 shadow-2xl flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out border-r border-slate-200">
        
        <!-- Drawer Header -->
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-purple-50 to-pink-50">
            <a href="{{ route('home') }}" onclick="toggleMobileDrawer(false)" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-xl shadow-md border border-purple-200 object-cover">
                <div>
                    <span class="text-sm font-black font-en gradient-text leading-tight block">Digital Mart BD</span>
                    <span class="text-[8px] text-slate-500 font-semibold tracking-wider uppercase">ডিজিটাল স্টোর</span>
                </div>
            </a>
            <button type="button" onclick="toggleMobileDrawer(false)" 
                    class="w-7 h-7 rounded-full bg-white hover:bg-rose-50 text-slate-500 hover:text-rose-600 flex items-center justify-center transition border border-slate-200 shadow-sm" 
                    aria-label="Close Menu">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Drawer Body Navigation Links -->
        <div class="flex-grow overflow-y-auto p-3 space-y-1 text-xs font-semibold text-slate-700">
            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider px-2 pt-1 pb-1">মেনু ও ক্যাটালগ</div>
            
            <a href="{{ route('home') }}" onclick="toggleMobileDrawer(false)" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 hover:text-brand-purple transition {{ request()->routeIs('home') ? 'bg-purple-50 text-brand-purple font-bold' : '' }}">
                <i class="fa-solid fa-house text-purple-600 w-4 text-center"></i>
                <span>হোম (Home)</span>
            </a>

            <a href="{{ route('product.details', 'gemini-pro-18m') }}" onclick="toggleMobileDrawer(false)" 
               class="flex items-center justify-between px-3 py-2 rounded-xl bg-gradient-to-r from-purple-500/10 to-pink-500/10 border border-purple-300/40 text-brand-purple font-bold transition">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-wand-magic-sparkles text-pink-600 w-4 text-center"></i>
                    <span>জেমিনাই প্রো ১৮ মাস</span>
                </div>
                <span class="bg-gradient-to-r from-purple-600 to-pink-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded uppercase">HOT</span>
            </a>

            <a href="{{ route('home') }}#trending-section" onclick="toggleMobileDrawer(false)" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 hover:text-brand-purple transition">
                <i class="fa-solid fa-fire text-amber-500 w-4 text-center"></i>
                <span>ট্রেন্ডিং প্রোডাক্টসমূহ</span>
            </a>

            <a href="{{ route('product.details', 'canva-pro-1-year') }}" onclick="toggleMobileDrawer(false)" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 hover:text-brand-purple transition">
                <i class="fa-solid fa-palette text-indigo-600 w-4 text-center"></i>
                <span>ক্যানভা প্রো (১ বছর)</span>
            </a>

            <a href="{{ route('home') }}#recent-section" onclick="toggleMobileDrawer(false)" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 hover:text-brand-purple transition">
                <i class="fa-solid fa-box-open text-blue-600 w-4 text-center"></i>
                <span>সকল সফটওয়্যার ও বান্ডেল</span>
            </a>

            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider px-2 pt-3 pb-1">কাস্টমার সার্ভিস</div>

            <a href="{{ route('order.track') }}" onclick="toggleMobileDrawer(false)" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 hover:text-brand-purple transition">
                <i class="fa-solid fa-truck-fast text-emerald-600 w-4 text-center"></i>
                <span>অর্ডার ট্র্যাক করুন (Track Order)</span>
            </a>

            <a href="https://wa.me/{{ $cleanWhatsApp }}" target="_blank" onclick="toggleMobileDrawer(false)" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 transition font-bold">
                <i class="fa-brands fa-whatsapp text-emerald-600 text-sm w-4 text-center"></i>
                <span>হোয়াটসঅ্যাপ হেল্পলাইন</span>
            </a>

            @if(!empty($settings['facebook_url']))
            <a href="{{ $settings['facebook_url'] }}" target="_blank" onclick="toggleMobileDrawer(false)" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-blue-50 text-blue-700 transition">
                <i class="fa-brands fa-facebook text-blue-600 w-4 text-center"></i>
                <span>ফেসবুক পেজ</span>
            </a>
            @endif

            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider px-2 pt-3 pb-1">সিকিউর অ্যাক্সেস</div>

            <a href="{{ route('admin.login') }}" onclick="toggleMobileDrawer(false)" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-100 text-slate-600 transition">
                <i class="fa-solid fa-shield-halved text-purple-600 w-4 text-center"></i>
                <span>অ্যাডমিন কন্ট্রোল প্যানেল</span>
            </a>
        </div>

        <!-- Drawer Footer -->
        <div class="p-3 border-t border-slate-100 bg-slate-50 text-center text-[11px] text-slate-500">
            <div class="flex items-center justify-center gap-1 text-emerald-700 font-bold mb-0.5">
                <i class="fa-solid fa-bolt text-yellow-500 text-[10px]"></i>
                <span>১-সেকেন্ড ইনস্ট্যান্ট ডেলিভারি</span>
            </div>
            <p class="text-[10px]">বিকাশ ও নগদ পেমেন্ট গ্রহণযোগ্য</p>
        </div>

    </div>

    <script>
        function toggleMobileDrawer(open) {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            if (!drawer || !overlay) return;

            if (open) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
                drawer.classList.remove('-translate-x-full');
                drawer.classList.add('translate-x-0');
                document.body.style.overflow = 'hidden';
            } else {
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                drawer.classList.remove('translate-x-0');
                drawer.classList.add('-translate-x-full');
                document.body.style.overflow = '';
            }
        }

        // Close drawer on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') toggleMobileDrawer(false);
        });
    </script>

    @yield('scripts')
</body>
</html>
