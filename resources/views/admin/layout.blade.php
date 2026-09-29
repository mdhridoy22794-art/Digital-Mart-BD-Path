<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'ড্যাশবোর্ড') - Digital Mart BD অ্যাডমিন প্যানেল</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Hind Siliguri"', 'sans-serif'],
                        bn: ['"Hind Siliguri"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#F5F3FF',
                            100: '#EDE9FE',
                            200: '#DDD6FE',
                            300: '#C4B5FD',
                            400: '#A78BFA',
                            500: '#8B5CF6',
                            600: '#7C3AED',
                            700: '#6D28D9',
                            800: '#5B21B6',
                            900: '#4C1D95',
                        },
                        dark: {
                            sidebar: '#0B0F19',
                            card: '#111827',
                            border: '#1F2937',
                        }
                    },
                    boxShadow: {
                        'glow-purple': '0 0 25px -5px rgba(124, 58, 237, 0.35)',
                        'glow-pink': '0 0 25px -5px rgba(236, 72, 153, 0.35)',
                        'glow-emerald': '0 0 25px -5px rgba(16, 185, 129, 0.35)',
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03)',
                        'card-hover': '0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif; background-color: #F8FAFC; }
        .gradient-brand { background: linear-gradient(135deg, #7C3AED 0%, #C026D3 50%, #EC4899 100%); }
        .gradient-dark-card { background: linear-gradient(145deg, #131B2E 0%, #0B0F19 100%); }
        .gradient-glass { background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%); }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
        
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #1E293B; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #334155; }
        
        /* Toast notification animation */
        @keyframes slideInDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-toast { animation: slideInDown 0.3s ease-out forwards; }
    </style>
</head>
<body class="h-full flex text-slate-800 antialiased overflow-hidden selection:bg-brand-500 selection:text-white">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

    <!-- Sidebar -->
    <aside id="adminSidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-dark-sidebar border-r border-slate-800 text-slate-300 flex flex-col shrink-0 transition-transform duration-300 -translate-x-full lg:translate-x-0 h-full">
        
        <!-- Sidebar Brand Header -->
        <div class="p-6 border-b border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="relative">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-11 h-11 rounded-2xl object-cover ring-2 ring-brand-500/50 shadow-glow-purple">
                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-500 border-2 border-dark-sidebar rounded-full"></span>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-white font-extrabold text-base tracking-tight">Digital Mart</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-brand-500 text-white font-mono">BD</span>
                    </div>
                    <span class="text-[11px] text-purple-400 font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                        <span>Admin Control Hub</span>
                    </span>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-grow overflow-y-auto sidebar-scroll p-4 space-y-6">
            
            <!-- Quick Stats Pill (Mini) -->
            <div class="p-3.5 rounded-2xl gradient-dark-card border border-purple-500/20 text-xs shadow-lg">
                <div class="flex items-center justify-between text-[11px] text-slate-400 mb-2">
                    <span class="font-medium flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        সিস্টেম স্ট্যাটাস
                    </span>
                    <span class="text-emerald-400 font-bold font-mono">ACTIVE</span>
                </div>
                <div class="text-[11px] text-slate-300">
                    অটো ডেলিভারি কিউ: <span class="text-white font-semibold font-mono">100% Operational</span>
                </div>
            </div>

            <!-- Menu Group: Management -->
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 font-bn">মেইন মেনু</span>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-brand-600 to-purple-600 text-white font-bold shadow-lg shadow-purple-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'bg-slate-800/80 text-purple-400 group-hover:bg-brand-600 group-hover:text-white' }}">
                            <i class="fa-solid fa-chart-pie text-xs"></i>
                        </div>
                        <span class="font-bn text-sm">ড্যাশবোর্ড (Dashboard)</span>
                    </div>
                    @if(request()->routeIs('admin.dashboard'))
                    <i class="fa-solid fa-chevron-right text-[10px] text-white/70"></i>
                    @endif
                </a>

                <a href="{{ route('admin.gemini') }}" 
                   class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('admin.gemini') ? 'bg-gradient-to-r from-brand-600 to-purple-600 text-white font-bold shadow-lg shadow-purple-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('admin.gemini') ? 'bg-white/20 text-white' : 'bg-slate-800/80 text-amber-400 group-hover:bg-brand-600 group-hover:text-white' }}">
                            <i class="fa-solid fa-sparkles text-xs"></i>
                        </div>
                        <span class="font-bn text-sm">জেমিনাই অফার ও পোস্ট</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold {{ request()->routeIs('admin.gemini') ? 'bg-white/20 text-white' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                        Ads Offer
                    </span>
                </a>

                <a href="{{ route('admin.links') }}" 
                   class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('admin.links') ? 'bg-gradient-to-r from-brand-600 to-purple-600 text-white font-bold shadow-lg shadow-purple-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('admin.links') ? 'bg-white/20 text-white' : 'bg-slate-800/80 text-emerald-400 group-hover:bg-brand-600 group-hover:text-white' }}">
                            <i class="fa-solid fa-link text-xs"></i>
                        </div>
                        <span class="font-bn text-sm">লিংক স্টক (Stock Pool)</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold {{ request()->routeIs('admin.links') ? 'bg-white/20 text-white' : 'bg-slate-800 text-emerald-400 border border-emerald-500/30' }}">
                        Pool
                    </span>
                </a>

                <a href="{{ route('admin.orders') }}" 
                   class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('admin.orders') ? 'bg-gradient-to-r from-brand-600 to-purple-600 text-white font-bold shadow-lg shadow-purple-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('admin.orders') ? 'bg-white/20 text-white' : 'bg-slate-800/80 text-pink-400 group-hover:bg-brand-600 group-hover:text-white' }}">
                            <i class="fa-solid fa-cart-shopping text-xs"></i>
                        </div>
                        <span class="font-bn text-sm">অর্ডার তালিকা (Orders)</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold {{ request()->routeIs('admin.orders') ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                        Live
                    </span>
                </a>
            </div>

            <!-- Menu Group: Settings & Integration -->
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 font-bn">সেটিংস ও ইন্টিগ্রেশন</span>

                <a href="{{ route('admin.settings') }}" 
                   class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-gradient-to-r from-brand-600 to-purple-600 text-white font-bold shadow-lg shadow-purple-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('admin.settings') ? 'bg-white/20 text-white' : 'bg-slate-800/80 text-blue-400 group-hover:bg-brand-600 group-hover:text-white' }}">
                            <i class="fa-solid fa-sliders text-xs"></i>
                        </div>
                        <span class="font-bn text-sm">সাইট ও পিক্সেল সেটিংস</span>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                </a>

                <a href="{{ route('home') }}" target="_blank" 
                   class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/70 text-xs transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-800/80 text-amber-400 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </div>
                        <span class="font-bn text-sm">মূল ওয়েবসাইট ভিজিট করুন</span>
                    </div>
                    <i class="fa-solid fa-external-link-alt text-[10px] text-slate-500 group-hover:text-amber-400 transition"></i>
                </a>
            </div>

        </div>

        <!-- Sidebar Footer / Admin Profile -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/50">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl gradient-brand flex items-center justify-center text-white font-bold font-mono text-sm shadow">
                        A
                    </div>
                    <div class="leading-tight">
                        <span class="text-xs font-bold text-white block">Administrator</span>
                        <span class="text-[10px] text-slate-400 font-mono truncate max-w-[130px] block">admin@digitalmartbd.com</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-slate-800/80 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 border border-slate-700/50 hover:border-rose-500/30 text-xs font-medium transition duration-200">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="font-bn">লগআউট করুন</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0 h-full overflow-hidden bg-slate-50/70">
        
        <!-- Top Navbar -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-8 py-3.5 flex items-center justify-between shrink-0 sticky top-0 z-30">
            
            <div class="flex items-center gap-3">
                <!-- Mobile toggle -->
                <button onclick="toggleSidebar()" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 font-bn tracking-tight flex items-center gap-2">
                        @yield('page_title', 'ড্যাশবোর্ড')
                    </h1>
                    <p class="text-[11px] text-slate-400 font-bn hidden sm:block">@yield('page_subtitle', 'ডিজিটাল মার্ট বিডি কন্ট্রোল প্যানেল')</p>
                </div>
            </div>

            <!-- Top Right Action Cluster -->
            <div class="flex items-center gap-3">
                <!-- Live Clock -->
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100/80 border border-slate-200 text-xs text-slate-600 font-mono shadow-sm">
                    <i class="fa-regular fa-clock text-brand-600"></i>
                    <span id="liveClock">{{ date('d M, Y - h:i A') }}</span>
                </div>

                <!-- Live Website Quick Link -->
                <a href="{{ route('home') }}" target="_blank" 
                   class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-brand-500 text-slate-700 hover:text-brand-600 text-xs font-semibold shadow-sm transition group">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-bn hidden sm:inline">লাইভ সাইট</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 group-hover:text-brand-600 transition"></i>
                </a>

                <!-- Admin Avatar Pill -->
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                    <div class="w-8 h-8 rounded-full gradient-brand text-white flex items-center justify-center font-bold text-xs shadow-sm ring-2 ring-purple-100">
                        <i class="fa-solid fa-user-shield text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Toast Notifications -->
        <div class="px-4 sm:px-8 pt-4">
            @if(session('success'))
            <div class="animate-toast p-4 rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 border border-emerald-400 flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-check text-base"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xs block font-bn text-emerald-50">সফল হয়েছে!</span>
                        <span class="text-xs font-medium font-bn text-white">{{ session('success') }}</span>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="animate-toast p-4 rounded-2xl bg-rose-500 text-white shadow-lg shadow-rose-500/20 border border-rose-400 flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xs block font-bn text-rose-50">ত্রুটি!</span>
                        <span class="text-xs font-medium font-bn text-white">{{ session('error') }}</span>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif
        </div>

        <!-- Scrollable Main Container -->
        <main class="flex-grow overflow-y-auto px-4 sm:px-8 py-6">
            @yield('content')
        </main>
    </div>

    <!-- Sidebar Mobile Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // Live Clock updater
        function updateClock() {
            const el = document.getElementById('liveClock');
            if (!el) return;
            const now = new Date();
            const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            el.innerText = now.toLocaleString('en-US', options);
        }
        setInterval(updateClock, 1000);

        // Copy Helper function with animated visual feedback
        function copyText(text, btnElement) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btnElement.innerHTML;
                btnElement.innerHTML = '<i class="fa-solid fa-check text-emerald-400"></i> Copied!';
                btnElement.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                setTimeout(() => {
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                }, 2000);
            }).catch(err => {
                prompt("লিংকটি কপি করুন:", text);
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
