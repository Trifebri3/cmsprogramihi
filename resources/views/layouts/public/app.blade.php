<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-theme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>{{ $currentProgram->name }} - {{ $pageTitle ?? 'Portal' }}</title>

    <!-- SEO Metadata -->
    <meta name="description" content="{{ $seoDescription ?? $currentProgram->name }}">
    <meta name="keywords" content="{{ $seoKeywords ?? 'PPMS, ' . $currentProgram->name }}">

    <!-- Dynamic Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($currentProgram->theme->font_heading ?? 'Poppins') }}:wght@400;500;600;700&family={{ urlencode($currentProgram->theme->font_body ?? 'Inter') }}:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Favicon Configuration -->
    @if($currentProgram->favicon_path)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $currentProgram->favicon_path) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Styles and Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flowbite CSS & JS CDN (Backup/Easy access) -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js" defer></script>

    <!-- Dynamic Custom Styling Variables -->
    <style>
        :root {
            --color-primary: {{ $currentProgram->theme->primary_color ?? '#2E7D32' }};
            --color-secondary: {{ $currentProgram->theme->secondary_color ?? '#FFFFFF' }};
            --color-accent: {{ $currentProgram->theme->accent_color ?? '#FFB300' }};
            --font-heading: '{{ $currentProgram->theme->font_heading ?? 'Poppins' }}', sans-serif;
            --font-body: '{{ $currentProgram->theme->font_body ?? 'Inter' }}', sans-serif;
            --ds-radius: {{ ($currentProgram->theme->layout_config['design_system']['radius'] ?? 'rounded-2xl') === 'rounded-none' ? '0px' : (($currentProgram->theme->layout_config['design_system']['radius'] ?? 'rounded-2xl') === 'rounded-xl' ? '12px' : (($currentProgram->theme->layout_config['design_system']['radius'] ?? 'rounded-2xl') === 'rounded-2xl' ? '16px' : '32px')) }};
            --ds-border-width: {{ ($currentProgram->theme->layout_config['design_system']['border_width'] ?? 'border') === 'border-0' ? '0px' : (($currentProgram->theme->layout_config['design_system']['border_width'] ?? 'border') === 'border' ? '1px' : '2px') }};
        }

        body {
            font-family: var(--font-body);
            background-color: #FAFAF9;
            color: #1F2937;
            transition: background-color 0.2s, color 0.2s;
        }
        .dark body {
            background-color: #0B0F19;
            color: #E2E8F0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            color: #111827;
            transition: color 0.2s;
        }
        .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 {
            color: #F8FAFC;
        }

        /* Dynamic Tailwind Overrides */
        .theme-primary-bg { background-color: var(--color-primary); }
        .theme-primary-text { color: var(--color-primary); }
        .theme-primary-border { border-color: var(--color-primary); }
        
        .theme-secondary-bg { background-color: var(--color-secondary); }
        .theme-secondary-text { color: var(--color-secondary); }
        
        .theme-accent-bg { background-color: var(--color-accent); }
        .theme-accent-text { color: var(--color-accent); }
        .theme-accent-border { border-color: var(--color-accent); }

        .theme-primary-btn {
            background-color: var(--color-primary);
            color: var(--color-secondary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-primary-btn:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px var(--color-primary);
        }
        .theme-primary-btn:active {
            transform: translateY(0px);
        }

        /* Glassmorphism utility */
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.4);
            transition: background-color 0.2s, border-color 0.2s;
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.8);
            border-bottom: 1px solid rgba(51, 65, 85, 0.4);
        }

        /* Canva-style aesthetics */
        .canva-card {
            background: #ffffff;
            border: var(--ds-border-width) solid rgba(229, 231, 235, 0.6);
            border-radius: var(--ds-radius);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dark .canva-card {
            background: #1E293B;
            border-color: rgba(51, 65, 85, 0.4);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        }
        .canva-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08);
        }
        .dark .canva-card:hover {
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.4);
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #D1D5DB;
        }

        {!! $currentProgram->theme->custom_css ?? '' !!}
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between bg-stone-50/40">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 glass-nav">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ mobileMenuOpen: false }">
            <div class="flex justify-between h-20">
                <!-- Left: Logo & Menus -->
                <div class="flex items-center w-full justify-between md:justify-start">
                    <a href="{{ $currentProgram->url('/') }}" class="flex-shrink-0 flex items-center gap-2 group transition-transform hover:scale-102">
                        @if($currentProgram->logo_path)
                            <img class="h-10 w-auto object-contain transition-transform group-hover:rotate-2" src="{{ asset('storage/' . $currentProgram->logo_path) }}" alt="{{ $currentProgram->name }}">
                        @else
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl theme-primary-bg flex items-center justify-center text-white font-black text-xl shadow-md shadow-primary/20 transition-transform group-hover:-rotate-3">
                                    {{ substr($currentProgram->name, 0, 1) }}
                                </div>
                                <span class="text-xl font-bold tracking-tight text-slate-800">{{ $currentProgram->name }}</span>
                            </div>
                        @endif
                    </a>
 
                    <!-- Navigation Items -->
                    <div class="hidden md:ml-10 md:flex md:items-center md:space-x-1.5 lg:space-x-3">
                        <!-- Home -->
                        <a href="{{ $currentProgram->url('/') }}" class="px-3 py-2 rounded-xl text-sm font-semibold tracking-wide transition-all {{ request()->is('/') || request()->is('p/'.$currentProgram->slug) ? 'theme-primary-bg theme-secondary-text shadow-sm shadow-primary/10' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            Home
                        </a>

                        @foreach($currentProgram->menus()->whereNull('parent_id')->get() as $menu)
                            @if($menu->children->count() > 0)
                                <div class="relative inline-flex items-center" x-data="{ open: false }" @click.away="open = false">
                                    <button @click="open = !open" class="px-3 py-2 rounded-xl text-sm font-semibold tracking-wide text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white inline-flex items-center gap-1">
                                        <span>{{ $menu->name }}</span>
                                        <svg class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2" x-transition:enter-end="transform opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="transform opacity-100 scale-100 translate-y-0" x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2" class="absolute left-0 mt-36 w-52 rounded-2xl shadow-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-2 z-50 space-y-0.5" style="display: none;">
                                        @foreach($menu->children as $child)
                                            <a href="{{ str_starts_with($child->url, 'http') ? $child->url : $currentProgram->url($child->url) }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:theme-primary-text transition">
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ str_starts_with($menu->url, 'http') ? $menu->url : $currentProgram->url($menu->url) }}" class="px-3 py-2 rounded-xl text-sm font-semibold tracking-wide text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition">
                                    {{ $menu->name }}
                                </a>
                            @endif
                        @endforeach


                    </div>
                </div>

                <!-- Right Side: Login & Theme Toggle -->
                <div class="hidden md:flex items-center gap-3">
                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" type="button" class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-700 rounded-xl text-xs p-2.5 transition active:scale-95">
                        <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95a1 1 0 11-1.414-1.414 1 1 0 011.414 1.414zm-9.9-9.9a1 1 0 00-1.414 1.414 1 1 0 001.414-1.414zm2.12 11.085a1 1 0 10-1.415-1.414 1 1 0 001.415 1.414zm9.9-9.9a1 1 0 00-1.414-1.414 1 1 0 001.414 1.414zM11 17a1 1 0 11-2 0v-1a1 1 0 112 0v1zm-7.778-.172a1 1 0 001.414-1.414 1 1 0 00-1.414 1.414zm12.444-12.444a1 1 0 001.414-1.414 1 0 00-1.414 1.414z" clip-rule="evenodd"></path></svg>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="theme-primary-btn px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-primary/10 transition-transform active:scale-95">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                            Log In
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button & Mobile Theme Toggle -->
                <div class="flex items-center md:hidden gap-2">
                    <button id="theme-toggle-mobile" type="button" class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-700 rounded-xl text-xs p-2.5 transition active:scale-95">
                        <svg id="theme-toggle-dark-icon-mobile" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon-mobile" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95a1 1 0 11-1.414-1.414 1 1 0 011.414 1.414zm-9.9-9.9a1 1 0 00-1.414 1.414 1 1 0 001.414-1.414zm2.12 11.085a1 1 0 10-1.415-1.414 1 1 0 001.415 1.414zm9.9-9.9a1 1 0 00-1.414-1.414 1 1 0 001.414 1.414zM11 17a1 1 0 11-2 0v-1a1 1 0 112 0v1zm-7.778-.172a1 1 0 001.414-1.414 1 1 0 00-1.414 1.414zm12.444-12.444a1 1 0 001.414-1.414 1 0 00-1.414 1.414z" clip-rule="evenodd"></path></svg>
                    </button>

                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2.5 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation panel -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="md:hidden pb-6 pt-2 border-t border-slate-100" style="display: none;">
                <div class="space-y-1">
                    <a href="{{ $currentProgram->url('/') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold transition {{ request()->is('/') || request()->is('p/'.$currentProgram->slug) ? 'theme-primary-bg theme-secondary-text shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">Home</a>
                    
                    @foreach($currentProgram->menus()->whereNull('parent_id')->get() as $menu)
                        @if($menu->children->count() > 0)
                            <div class="px-4 py-2 space-y-1.5">
                                <span class="text-xs uppercase text-slate-400 font-bold tracking-wider">{{ $menu->name }}</span>
                                <div class="pl-3 border-l border-slate-200 space-y-1">
                                    @foreach($menu->children as $child)
                                        <a href="{{ str_starts_with($child->url, 'http') ? $child->url : $currentProgram->url($child->url) }}" class="block py-2 text-slate-600 hover:text-slate-900 text-sm font-medium">
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ str_starts_with($menu->url, 'http') ? $menu->url : $currentProgram->url($menu->url) }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                {{ $menu->name }}
                            </a>
                        @endif
                    @endforeach



                    <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between px-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="theme-primary-btn w-full text-center py-2.5 rounded-xl text-sm font-bold shadow-md shadow-primary/10">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="w-full text-center py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-sm font-bold transition">Log In</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Dynamic Splash / Popup Ad Banner overlay -->
    @php
        $pb = $currentProgram->theme->layout_config['popup_banner'] ?? null;
        $showPopup = false;
        if ($pb && !empty($pb['is_active']) && $pb['is_active'] == true) {
            $today = date('Y-m-d');
            $startOk = empty($pb['start_date']) || $today >= $pb['start_date'];
            $endOk = empty($pb['end_date']) || $today <= $pb['end_date'];
            if ($startOk && $endOk) {
                $showPopup = true;
            }
        }
    @endphp

    @if($showPopup)
        <div 
            x-data="{ 
                open: false,
                init() {
                    const mode = '{{ $pb['trigger_mode'] ?? 'once' }}';
                    const bannerId = '{{ md5($pb['media_url'] ?? '') }}';
                    if (mode === 'once') {
                        if (!localStorage.getItem('popup_shown_' + bannerId)) {
                            setTimeout(() => { this.open = true; }, 1200);
                            localStorage.setItem('popup_shown_' + bannerId, 'true');
                        }
                    } else {
                        setTimeout(() => { this.open = true; }, 1200);
                    }
                }
            }"
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4"
            style="display: none;"
            @keydown.escape.window="open = false"
        >
            <div 
                @click.away="open = false" 
                class="bg-white rounded-[32px] overflow-hidden max-w-lg w-full shadow-2xl border border-slate-100 relative p-6 space-y-6"
            >
                <button @click="open = false" class="absolute top-4 right-4 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full w-8 h-8 flex items-center justify-center font-bold transition focus:outline-none z-10">
                    ✕
                </button>
                @if(!empty($pb['media_url']))
                    <div class="rounded-[24px] overflow-hidden aspect-video bg-slate-50 border border-slate-100">
                        <img class="w-full h-full object-cover" src="{{ $pb['media_url'] }}" alt="Announcement Banner">
                    </div>
                @endif
                @if(!empty($pb['cta_text']))
                    <div class="text-center pt-2">
                        <a href="{{ $pb['cta_url'] ?? '#' }}" target="_blank" class="inline-flex items-center gap-2 px-8 py-3.5 theme-primary-btn font-extrabold rounded-full transition shadow-lg text-xs uppercase tracking-wider">
                            {{ $pb['cta_text'] }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 py-16 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
                <!-- Branding column -->
                <div class="md:col-span-5 space-y-5">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg theme-primary-bg flex items-center justify-center text-white font-extrabold text-base">
                            {{ substr($currentProgram->name, 0, 1) }}
                        </div>
                        <span class="text-white font-black text-xl tracking-tight">{{ $currentProgram->name }}</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                        Platform kolaborasi terpadu untuk menyebarkan dampak positif secara berkelanjutan. Satu wadah untuk perubahan sosial dan transparansi publik.
                    </p>
                    @if($currentProgram->getContact('address'))
                        <div class="text-xs text-slate-500 pt-4 border-t border-slate-900 max-w-sm">
                            <span class="block font-bold text-slate-400 uppercase tracking-widest mb-1.5 text-[10px]">Kantor Sekretariat:</span>
                            <span class="leading-relaxed block">{{ $currentProgram->getContact('address') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Links column -->
                <div class="md:col-span-3 space-y-4">
                    <h3 class="text-white font-bold text-xs uppercase tracking-widest relative pb-2 after:absolute after:bottom-0 after:left-0 after:w-8 after:h-0.5 after:theme-primary-bg">Navigasi Portal</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ $currentProgram->url('/') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full theme-primary-bg"></span>Home</a></li>
                        <li><a href="{{ $currentProgram->url('/management') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full theme-primary-bg"></span>Pengurus</a></li>
                        <li><a href="{{ $currentProgram->url('/gallery') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full theme-primary-bg"></span>Galeri Kegiatan</a></li>
                        <li><a href="{{ $currentProgram->url('/documents') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full theme-primary-bg"></span>Repositori Dokumen</a></li>
                    </ul>
                </div>

                <!-- Contact/Meta column -->
                <div class="md:col-span-4 space-y-4">
                    <h3 class="text-white font-bold text-xs uppercase tracking-widest relative pb-2 after:absolute after:bottom-0 after:left-0 after:w-8 after:h-0.5 after:theme-primary-bg">Hubungi Kami</h3>
                    <ul class="space-y-3.5 text-sm">
                        @php
                            $contact = $currentProgram->contact;
                            if (is_string($contact)) {
                                $contact = json_decode($contact, true);
                            }
                        @endphp
                        @if(is_array($contact) && count($contact) > 0)
                            @foreach($contact as $key => $item)
                                @php
                                    $label = $item['label'] ?? '';
                                    $val = $item['value'] ?? '';
                                @endphp
                                @if($val)
                                    @if(str_contains($val, '<iframe'))
                                        <li class="mt-4">
                                            <span class="block font-bold text-white/80 text-xs mb-2">{{ $label }}:</span>
                                            <div class="w-full overflow-hidden rounded-2xl h-36 opacity-75 hover:opacity-100 transition shadow-inner border border-slate-900">
                                                {!! $val !!}
                                            </div>
                                        </li>
                                    @else
                                        <li class="flex items-start gap-2.5">
                                            <span class="font-semibold text-white/80 min-w-[80px] shrink-0 text-xs text-slate-300">{{ $label }}:</span>
                                            <div class="flex-grow text-slate-400">
                                                @if(filter_var($val, FILTER_VALIDATE_URL))
                                                    <a href="{{ $val }}" target="_blank" class="hover:text-white transition break-all underline decoration-dotted text-emerald-400 font-medium">{{ $val }}</a>
                                                @else
                                                    <span class="break-all font-medium">{{ $val }}</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        @else
                            <p class="text-xs text-slate-500 italic">Belum ada informasi kontak resmi.</p>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="mt-16 border-t border-slate-900 pt-8 text-center text-xs text-slate-600 font-semibold tracking-wide flex flex-col sm:flex-row justify-between items-center gap-4">
                <span>&copy; {{ date('Y') }} {{ $currentProgram->name }}. All rights reserved.</span>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-400 transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-slate-400 transition-colors">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        var themeToggleDarkIconMobile = document.getElementById('theme-toggle-dark-icon-mobile');
        var themeToggleLightIconMobile = document.getElementById('theme-toggle-light-icon-mobile');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-theme: dark)').matches)) {
            if (themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
            if (themeToggleLightIconMobile) themeToggleLightIconMobile.classList.remove('hidden');
        } else {
            if (themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
            if (themeToggleDarkIconMobile) themeToggleDarkIconMobile.classList.remove('hidden');
        }

        function toggleTheme() {
            if (themeToggleDarkIcon) themeToggleDarkIcon.classList.toggle('hidden');
            if (themeToggleLightIcon) themeToggleLightIcon.classList.toggle('hidden');
            if (themeToggleDarkIconMobile) themeToggleDarkIconMobile.classList.toggle('hidden');
            if (themeToggleLightIconMobile) themeToggleLightIconMobile.classList.toggle('hidden');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        }

        var themeToggleBtn = document.getElementById('theme-toggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', toggleTheme);
        }

        var themeToggleBtnMobile = document.getElementById('theme-toggle-mobile');
        if (themeToggleBtnMobile) {
            themeToggleBtnMobile.addEventListener('click', toggleTheme);
        }
    </script>
</body>
</html>
