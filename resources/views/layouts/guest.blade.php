<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal CMS') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.95); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex items-stretch">
        <!-- Split Layout Wrapper -->
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 min-h-screen">
            
            <!-- Left Panel: Graphic & Intro (Hidden on mobile) -->
            <div class="hidden lg:flex lg:col-span-5 xl:col-span-6 bg-gradient-to-tr from-emerald-950 via-teal-900 to-slate-900 text-white relative overflow-hidden flex-col justify-between p-16">
                <!-- Background animations -->
                <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-blob animation-delay-2000"></div>
                
                <!-- Header Branding -->
                <div class="relative z-10 flex items-center gap-3">
                    <img src="{{ asset('images/logoterang.png') }}" alt="Institut Hijau Indonesia Logo" class="h-12 w-auto">
                    <div class="flex flex-col text-left">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#84A98C] leading-none">Institut Hijau Indonesia</span>
                        <span class="text-lg font-extrabold tracking-tight text-white mt-1">Profil & CMS Program</span>
                    </div>
                </div>

                <!-- Mid-Intro Content (Mock Dashboard UI & Text) -->
                <div class="relative z-10 my-auto max-w-lg space-y-8">
                    <div class="space-y-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                            Super Responsive & Modern
                        </span>
                        <h1 class="text-4xl xl:text-5xl font-black leading-tight tracking-tight">
                            Manage all your websites in <span class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">one beautiful desk.</span>
                        </h1>
                        <p class="text-slate-300 text-sm leading-relaxed">
                            A fully-integrated content manager offering customized layouts, LPJ document repositories, and interactive committee directories for Institut Hijau Indonesia's environmental programs.
                        </p>
                    </div>

                    <!-- Small Canva-style feature card grid -->
                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-2">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                            <h3 class="font-bold text-xs">Custom Templates</h3>
                            <p class="text-[10px] text-slate-400">NGO, CSR, and Tech layouts.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-2">
                            <svg class="w-6 h-6 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <h3 class="font-bold text-xs">Arsip LPJ / SOP</h3>
                            <p class="text-[10px] text-slate-400">Transparansi dokumen publik.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Credit -->
                <div class="relative z-10 text-xs text-slate-400 flex items-center justify-between">
                    <span>&copy; {{ date('Y') }} Institut Hijau Indonesia.</span>
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                </div>
            </div>

            <!-- Right Panel: Login Form -->
            <div class="col-span-1 lg:col-span-7 xl:col-span-6 flex flex-col justify-center items-center p-8 sm:p-16 lg:p-24 bg-white relative">
                
                <!-- Tiny logo overlay for mobile view -->
                <div class="lg:hidden flex items-center gap-3 mb-10">
                    <img src="{{ asset('images/logogelap.png') }}" alt="Institut Hijau Indonesia Logo" class="h-10 w-auto">
                    <div class="flex flex-col text-left">
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 leading-none">Institut Hijau Indonesia</span>
                        <span class="text-base font-extrabold tracking-tight text-slate-800">Profil & CMS Program</span>
                    </div>
                </div>

                <!-- Main Slot Wrapper -->
                <div class="w-full max-w-md space-y-8">
                    <!-- Heading -->
                    <div class="space-y-2 text-center lg:text-left">
                        <h2 class="text-3xl font-black text-slate-900 leading-tight">Welcome back</h2>
                        <p class="text-sm text-slate-500">Sign in to manage and customize your program websites.</p>
                    </div>

                    <!-- The Form Slot -->
                    <div class="mt-8">
                        {{ $slot }}
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>
