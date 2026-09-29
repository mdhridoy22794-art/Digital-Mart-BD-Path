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
    <meta property="og:description" content="গুগল জেমিনাই প্রো ১৮ মাস মাত্র ২০০ টাকায়! ১ সেকেন্ডে ইনস্ট্যান্ট ডেলিভারি।">
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

    <!-- Meta Pixel Code -->
    @if(!empty($settings['meta_pixel_id']))
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $settings['meta_pixel_id'] }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" 
             src="https://www.facebook.com/tr?id={{ $settings['meta_pixel_id'] }}&ev=PageView&noscript=1"/>
    </noscript>
    @endif

    @if(!empty($settings['custom_header_script']))
        {!! $settings['custom_header_script'] !!}
    @endif
</head>
<body class="min-h-screen flex flex-col antialiased bg-slate-50 text-slate-800">

    <!-- Top Announcement Bar -->
    @if(!empty($settings['announcement_text']))
    <div class="gradient-brand text-white py-1.5 px-4 text-center text-xs font-semibold tracking-wide flex items-center justify-center gap-2 shadow-sm">
        <i class="fa-solid fa-fire text-yellow-300 animate-pulse"></i>
        <span>{{ $settings['announcement_text'] }}</span>
    </div>
    @endif

    <!-- Main Store Header -->
    <header class="bg-white border-b border-slate-200/90 sticky top-0 z-40 shadow-sm">
        <!-- Upper Row: Brand, Search, Contacts & Cart -->
        <div class="max-w-7xl mx-auto px-4 py-3.5 flex flex-wrap items-center justify-between gap-3">
            
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                <img src="{{ asset('images/logo.jpg') }}" alt="Digital Mart BD Logo" class="w-11 h-11 rounded-xl shadow-md border border-purple-200 object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="flex flex-col">
                    <span class="text-xl md:text-2xl font-black font-en gradient-text tracking-tight leading-none">Digital Mart BD</span>
                    <span class="text-[10px] text-slate-500 font-semibold tracking-wider uppercase mt-0.5">ডিজিটাল সলিউশন ও স্টোর</span>
                </div>
            </a>

            <!-- Central Product Search Bar -->
            <div class="flex-grow max-w-xl mx-auto order-3 sm:order-2 w-full sm:w-auto">
                <form action="{{ route('home') }}" method="GET" class="relative flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search for products... (যেমন: Gemini Pro, Canva, Tools)" 
                           class="w-full pl-4 pr-12 py-2.5 rounded-full border border-purple-200/80 bg-slate-50/60 text-xs md:text-sm focus:bg-white focus:border-brand-purple focus:ring-2 focus:ring-purple-100 outline-none transition duration-200">
                    <button type="submit" class="absolute right-1.5 w-8 h-8 rounded-full gradient-brand text-white flex items-center justify-center hover:opacity-90 transition shadow-sm">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </form>
            </div>

            <!-- Top Right Support & Action Icons -->
            <div class="flex items-center gap-3.5 order-2 sm:order-3 ml-auto sm:ml-0">
                <!-- WhatsApp Support Pill -->
                @if(!empty($settings['whatsapp_number']))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}" target="_blank" 
                   class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition text-emerald-800">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <i class="fa-brands fa-whatsapp text-emerald-600 text-base"></i>
                    <div class="hidden md:flex flex-col text-left">
                        <span class="text-[9px] text-emerald-700 font-bold uppercase leading-none">Chat Support</span>
                        <span class="text-[11px] font-bold font-en text-slate-900 leading-tight">{{ $settings['whatsapp_number'] }}</span>
                    </div>
                </a>
                @endif

                <!-- Track Order Button -->
                <a href="{{ route('order.track') }}" class="w-9 h-9 rounded-xl border border-slate-200 hover:border-purple-300 hover:bg-purple-50 flex items-center justify-center text-slate-700 hover:text-brand-purple transition" title="অর্ডার ট্র্যাক করুন">
                    <i class="fa-solid fa-truck-fast text-sm"></i>
                </a>

                <!-- Wishlist Icon -->
                <button class="w-9 h-9 rounded-xl border border-slate-200 hover:border-pink-300 hover:bg-pink-50 flex items-center justify-center text-slate-700 hover:text-pink-600 transition" title="উইশলিস্ট">
                    <i class="fa-regular fa-heart text-sm"></i>
                </button>

                <!-- Cart Badge Button -->
                <a href="{{ route('product.details', 'gemini-pro-18m') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl gradient-brand text-white shadow-md shadow-purple-500/20 btn-shine transition transform hover:scale-105">
                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                    <span class="text-xs font-bold font-en">৳200</span>
                </a>
            </div>

        </div>

        <!-- Lower Navigation Row: Categories & Nav Links -->
        <div class="border-t border-slate-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 flex items-center justify-between text-xs font-semibold overflow-x-auto no-scrollbar">
                
                <div class="flex items-center gap-1 sm:gap-2 py-2">
                    <!-- All Categories Dropdown Trigger -->
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-lg gradient-brand text-white flex items-center gap-2 font-bold shadow-sm hover:opacity-95 transition">
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
                                <a href="#products-section" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
                                    <i class="fa-solid fa-laptop-code text-blue-600 text-xs"></i>
                                    <span>Software & Utilities</span>
                                </a>
                                <a href="#products-section" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
                                    <i class="fa-solid fa-palette text-pink-600 text-xs"></i>
                                    <span>Canva & Graphic Design</span>
                                </a>
                                <a href="#products-section" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
                                    <i class="fa-solid fa-layer-group text-emerald-600 text-xs"></i>
                                    <span>Templates & Bundles</span>
                                </a>
                                <a href="#products-section" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-brand-purple transition">
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
                    <a href="#products-section" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition">
                        Shop
                    </a>
                    <a href="{{ route('product.details', 'gemini-pro-18m') }}" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition flex items-center gap-1">
                        <span>Gemini AI Pro</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500 animate-ping"></span>
                    </a>
                    <a href="#products-section" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition">
                        Software
                    </a>
                    <a href="#products-section" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition">
                        Templates
                    </a>
                    <a href="#footer-section" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-brand-purple hover:bg-slate-50 transition">
                        Our Contacts
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
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-10 h-10 rounded-xl border border-purple-500/30">
                        <span class="text-white text-xl font-bold font-en tracking-tight">Digital Mart BD</span>
                    </div>
                    <p class="text-slate-400 leading-relaxed text-[11px]">
                        Digital Mart BD হলো আসল ও নির্ভরযোগ্য প্রিমিয়াম সফটওয়্যার, এআই টুলস, এবং ডিজিটাল সাবস্ক্রিপশনের ওয়ান-স্টপ মার্কেটপ্লেস। আমরা শতভাগ গ্যারান্টি সহকারে অটোমেটিক ইনস্ট্যান্ট অ্যাক্সেস প্রদান করি।
                    </p>
                    <div class="space-y-2 text-[11px]">
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-purple-400"></i>
                            <span class="text-white font-en">{{ $settings['whatsapp_number'] ?? '+880 1934-779775' }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-pink-400"></i>
                            <span class="text-white">support@digitalmartbd.com</span>
                        </p>
                    </div>
                </div>

                <!-- Col 2: Customer Service -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider font-en">Customer Service</h4>
                    <ul class="space-y-2.5 text-[11px]">
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '8801934779775') }}" target="_blank" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Contact Us</a></li>
                        <li><a href="{{ route('order.track') }}" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Track Order</a></li>
                        <li><a href="#how-to-buy" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> How to Buy</a></li>
                        <li><a href="#faq" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> FAQ</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Col 3: Useful Links -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider font-en">Useful Links</h4>
                    <ul class="space-y-2.5 text-[11px]">
                        <li><a href="{{ route('home') }}" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> All Products</a></li>
                        <li><a href="{{ route('product.details', 'gemini-pro-18m') }}" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Gemini AI Pro 18M</a></li>
                        <li><a href="#products-section" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> AI Tools</a></li>
                        <li><a href="#products-section" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Software Licenses</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-purple-400 transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-[9px] text-purple-500"></i> Admin Panel</a></li>
                    </ul>
                </div>

                <!-- Col 4: Follow Us & Payment Methods -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider font-en">Follow Us</h4>
                    <p class="text-[11px] mb-3">Stay connected with our official channels:</p>
                    <div class="flex items-center gap-2 mb-6">
                        @if(!empty($settings['facebook_url']))
                        <a href="{{ $settings['facebook_url'] }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 hover:bg-blue-600 hover:border-blue-600 text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        @endif
                        @if(!empty($settings['whatsapp_number']))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 hover:bg-emerald-600 hover:border-emerald-600 text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </a>
                        @endif
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 hover:bg-cyan-500 hover:border-cyan-500 text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-telegram text-xs"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 hover:bg-red-600 hover:border-red-600 text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-youtube text-xs"></i>
                        </a>
                    </div>

                    <h5 class="text-white font-semibold text-xs mb-2">WE ACCEPT:</h5>
                    <div class="flex flex-wrap gap-2 text-[10px] font-bold">
                        <span class="bg-pink-950/60 border border-pink-700/50 text-pink-300 px-2 py-1 rounded">bKash</span>
                        <span class="bg-orange-950/60 border border-orange-700/50 text-orange-300 px-2 py-1 rounded">Nagad</span>
                        <span class="bg-purple-950/60 border border-purple-700/50 text-purple-300 px-2 py-1 rounded">Rocket</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Subfooter -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 gap-3">
                <p>© {{ date('Y') }} Digital Mart BD. All rights reserved.</p>
                <p class="text-slate-600">Built with Laravel & Ultra-Speed Architecture</p>
            </div>

        </div>
    </footer>

    <!-- Floating WhatsApp Bubble -->
    @if(!empty($settings['whatsapp_number']))
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}" target="_blank"
       class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center shadow-xl shadow-emerald-500/40 transform hover:scale-110 transition duration-300">
        <i class="fa-brands fa-whatsapp text-3xl"></i>
    </a>
    @endif

    @yield('scripts')
</body>
</html>
