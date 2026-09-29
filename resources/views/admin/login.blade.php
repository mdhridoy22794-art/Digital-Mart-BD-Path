<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাডমিন লগইন - Digital Mart BD</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Hind Siliguri"', 'sans-serif'],
                        bn: ['"Hind Siliguri"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            purple: '#7C3AED',
                            violet: '#6A11CB',
                            pink: '#EC4899',
                        }
                    },
                    boxShadow: {
                        'glow-purple': '0 0 35px -5px rgba(124, 58, 237, 0.4)',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif; background-color: #070B14; }
        .gradient-brand { background: linear-gradient(135deg, #7C3AED 0%, #C026D3 50%, #EC4899 100%); }
    </style>
</head>
<body class="min-h-screen bg-[#070B14] flex items-center justify-center p-4 relative overflow-hidden selection:bg-brand-purple selection:text-white">

    <!-- Background Ambient Glow Orbs -->
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-purple-600/20 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-pink-600/20 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full bg-blue-600/10 blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Card -->
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl border border-slate-800/90 overflow-hidden">
            
            <!-- Top Visual Header -->
            <div class="p-8 pb-6 text-center border-b border-slate-800/60 relative">
                <div class="relative inline-block mb-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-2xl mx-auto shadow-glow-purple ring-2 ring-purple-500/50 object-cover">
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                </div>
                <h1 class="text-xl font-extrabold text-white tracking-tight flex items-center justify-center gap-1.5">
                    <span>Digital Mart BD</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-brand-purple text-white">PRO</span>
                </h1>
                <p class="text-xs text-slate-400 font-bn mt-1">অ্যাডমিন কন্ট্রোল সেন্টার ও সুরক্ষিত ড্যাশবোর্ড</p>
            </div>

            <!-- Form Body -->
            <div class="p-8 pt-6">
                
                @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-semibold mb-5 flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm shrink-0"></i>
                    <span class="font-bn">{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2 font-bn">
                            অ্যাডমিন ইমেইল ঠিকানা
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </div>
                            <input type="email" id="emailInput" name="email" value="{{ old('email') }}" placeholder="admin@digitalmartbd.com" required autocomplete="email"
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl bg-slate-800/70 border border-slate-700 text-xs text-white placeholder-slate-500 focus:border-brand-purple focus:ring-2 focus:ring-purple-500/20 outline-none transition font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2 font-bn">
                            গোপন পাসওয়ার্ড
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" id="passwordInput" name="password" required placeholder="••••••••" autocomplete="current-password"
                                   class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-800/70 border border-slate-700 text-xs text-white placeholder-slate-500 focus:border-brand-purple focus:ring-2 focus:ring-purple-500/20 outline-none transition font-mono">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition">
                                <i id="eyeIcon" class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-400 pt-1 font-bn">
                        <label class="flex items-center gap-2 cursor-pointer hover:text-slate-300 transition">
                            <input type="checkbox" name="remember" class="accent-purple-600 rounded">
                            <span>লগইন মনে রাখুন</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full py-3.5 px-4 rounded-2xl text-white font-bold text-xs gradient-brand hover:opacity-95 transition shadow-lg shadow-purple-600/30 flex items-center justify-center gap-2 mt-4 font-bn tracking-wide">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        <span>অ্যাডমিন ড্যাশবোর্ডে প্রবেশ করুন</span>
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-800/80 text-center">
                    <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-white transition inline-flex items-center gap-2 font-bn">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>মূল ওয়েবসাইটে ফিরে যান</span>
                    </a>
                </div>

            </div>

        </div>

        <p class="text-center text-[11px] text-slate-600 mt-6 font-bn">
            &copy; {{ date('Y') }} Digital Mart BD. সকল অধিকার সংরক্ষিত।
        </p>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
