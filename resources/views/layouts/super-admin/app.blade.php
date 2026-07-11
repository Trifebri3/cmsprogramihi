<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Super Admin Platform Panel - {{ config('app.name', 'PPMS') }}</title>

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
    <body class="bg-slate-50 antialiased">
        <div class="min-h-screen">
            <!-- Super Admin Navigation Header -->
            <nav class="bg-slate-900 border-b border-slate-800 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center gap-8">
                            <!-- Logo Induk (Dark) -->
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                <img src="{{ asset('images/logogelap.png') }}" class="h-8 object-contain" alt="Logo Induk PPMS">
                                <span class="font-bold text-lg tracking-wide uppercase text-indigo-400">PPMS Induk</span>
                            </a>

                            <!-- Nav links -->
                            <div class="hidden sm:flex sm:gap-4">
                                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-xl text-sm font-semibold hover:bg-slate-800 transition {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.programs.create') }}" class="px-3 py-2 rounded-xl text-sm font-semibold hover:bg-slate-800 transition {{ request()->routeIs('admin.programs.create') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                    Provision Program
                                </a>
                            </div>
                        </div>

                        <!-- User Profile settings dropdown simulation -->
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-semibold bg-indigo-500/20 text-indigo-300 px-3 py-1 rounded-full border border-indigo-500/30">
                                Super Admin Mode
                            </span>
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline-block">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-slate-300 hover:text-white hover:underline transition">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @hasSection('header')
                <header class="bg-white border-b border-slate-100 shadow-sm">
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
    </body>
</html>
