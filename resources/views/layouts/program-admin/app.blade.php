@php
    $user = auth()->user();
    $programId = $user->hasRole('super-admin') ? session('active_program_id') : $user->program_id;
    $program = $programId ? \App\Models\Program::find($programId) : null;
@endphp
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

        <title>Program Manager Panel - {{ $program ? $program->name : 'PPMS' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased transition-colors duration-200">
        <div class="min-h-screen">
            <!-- Program Admin Navigation Header -->
            <nav class="bg-white dark:bg-slate-900 border-b border-gray-150 dark:border-slate-800 text-gray-800 dark:text-slate-100 transition-colors duration-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center gap-8">
                            <!-- Program Logo branding -->
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                                @if($program && $program->logo_path)
                                    <img src="{{ asset('storage/' . $program->logo_path) }}" class="h-8 object-contain" alt="Logo Program">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs uppercase shadow-sm">
                                        {{ $program ? substr($program->name, 0, 2) : 'P' }}
                                    </div>
                                @endif
                                <span class="font-bold text-sm text-gray-900 dark:text-white tracking-tight block">
                                    {{ $program ? $program->name : 'Dashboard Program' }}
                                </span>
                            </a>

                            <!-- CMS Nav Links -->
                            <div class="hidden sm:flex sm:gap-2">
                                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('cms.contents.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('cms.contents.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    Kelola Artikel
                                </a>
                                @if($user->hasAnyRole(['super-admin', 'program-admin']))
                                    <a href="{{ route('cms.users.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('cms.users.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        Kelola User
                                    </a>
                                    <a href="{{ route('cms.categories.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('cms.categories.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        Kelola Kategori
                                    </a>
                                    <a href="{{ route('cms.periods.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('cms.periods.*') || request()->routeIs('cms.managements.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        Kelola Tim
                                    </a>
                                    <a href="{{ route('cms.albums.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('cms.albums.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        Kelola Galeri
                                    </a>
                                    <a href="{{ route('cms.documents.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('cms.documents.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        Kelola Dokumen
                                    </a>
                                    <a href="{{ route('cms.theme.edit') }}" class="px-3 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors {{ request()->routeIs('cms.theme.edit') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        Kustom Tampilan
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- User Profile logouts & Theme Toggle -->
                        <div class="flex items-center gap-4">
                            <!-- Theme Toggle Button -->
                            <button id="theme-toggle" type="button" class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-700 rounded-xl text-xs p-2.5 transition active:scale-95">
                                <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                                <svg id="theme-toggle-light-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95a1 1 0 11-1.414-1.414 1 0 011.414 1.414zm-9.9-9.9a1 1 0 00-1.414 1.414 1 0 001.414-1.414zm2.12 11.085a1 1 0 10-1.415-1.414 1 0 001.415 1.414zm9.9-9.9a1 1 0 00-1.414-1.414 1 0 001.414 1.414zM11 17a1 1 0 11-2 0v-1a1 1 0 112 0v1zm-7.778-.172a1 1 0 001.414-1.414 1 0 00-1.414 1.414zm12.444-12.444a1 1 0 001.414-1.414 1 0 00-1.414 1.414z" clip-rule="evenodd"></path></svg>
                            </button>

                            @if($user->hasRole('super-admin'))
                                <span class="text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 px-2 py-0.5 rounded-full select-none">
                                    Simulating Tenant
                                </span>
                            @endif

                            <div class="text-right hidden md:block">
                                <div class="text-xs font-bold text-gray-950 dark:text-white">{{ $user->name }}</div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 capitalize">{{ $user->roles->pluck('name')->first() }}</div>
                            </div>
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline-block">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-red-655 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hover:underline transition">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @hasSection('header')
                <header class="bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 shadow-sm transition-colors duration-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Main Content Area -->
            <main class="py-6">
                @yield('content')
            </main>
        </div>

        <script>
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Change the icons inside the button based on previous settings
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-theme: dark)').matches)) {
                if (themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
            } else {
                if (themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    // toggle icons inside button
                    themeToggleDarkIcon.classList.toggle('hidden');
                    themeToggleLightIcon.classList.toggle('hidden');

                    // if set via local storage previously
                    if (localStorage.getItem('color-theme')) {
                        if (localStorage.getItem('color-theme') === 'dark') {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('color-theme', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('color-theme', 'dark');
                        }
                    // if NOT set via local storage previously
                    } else {
                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('color-theme', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('color-theme', 'dark');
                        }
                    }
                });
            }
        </script>
    </body>
</html>
