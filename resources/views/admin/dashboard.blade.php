@extends('layouts.super-admin.app')

@section('header')
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('PPMS Platform Super Admin Dashboard') }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition shadow-sm">
                    Provision Program Baru
                </a>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                    Mode Super Admin
                </span>
            </div>
        </div>
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Programs Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <span class="text-sm text-gray-400 font-semibold uppercase block">Total Program</span>
                        <span class="text-3xl font-extrabold text-gray-900">{{ $totalPrograms }}</span>
                    </div>
                </div>

                <!-- Users Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 flex items-center gap-4">
                    <div class="p-3 bg-green-50 rounded-xl text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div>
                        <span class="text-sm text-gray-400 font-semibold uppercase block">Total Pengguna</span>
                        <span class="text-3xl font-extrabold text-gray-900">{{ $totalUsers }}</span>
                    </div>
                </div>

                <!-- Contents Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 flex items-center gap-4">
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2m2 2a2 2 0 01-2 2m2-2h-2m-6 4h4m-4 4h4m-6-8h2" /></svg>
                    </div>
                    <div>
                        <span class="text-sm text-gray-400 font-semibold uppercase block">Halaman & Artikel</span>
                        <span class="text-3xl font-extrabold text-gray-900">{{ $totalContents }}</span>
                    </div>
                </div>

                <!-- Documents Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 flex items-center gap-4">
                    <div class="p-3 bg-red-50 rounded-xl text-red-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <span class="text-sm text-gray-400 font-semibold uppercase block">Dokumen Unggahan</span>
                        <span class="text-3xl font-extrabold text-gray-900">{{ $totalDocuments }}</span>
                    </div>
                </div>
            </div>

            <!-- Program Selector & Active Context Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Context Switcher -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Pilih Konteks Program</h3>
                        <p class="text-xs text-gray-400 mt-1">Ganti target edit program aktif untuk modul CMS.</p>
                    </div>
                    
                    @if($programs->count() > 0)
                        <form action="{{ route('admin.select-program') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="program_id" class="block text-sm font-semibold text-gray-700 mb-2">Program Aktif:</label>
                                <select name="program_id" id="program_id" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                                    @foreach($programs as $p)
                                        <option value="{{ $p->id }}" {{ $activeProgram && $activeProgram->id == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->slug }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
                                Aktifkan Context
                            </button>
                        </form>
                    @else
                        <p class="text-gray-400 text-sm italic">Belum ada program.</p>
                    @endif
                </div>

                <!-- Active Program Detail -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 lg:col-span-2 space-y-6">
                    @if($activeProgram)
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 mb-2">
                                    Context Terpilih
                                </span>
                                <h3 class="font-bold text-xl text-gray-900">{{ $activeProgram->name }}</h3>
                                <p class="text-xs text-gray-500">Slug: {{ $activeProgram->slug }} | Subdomain: {{ $activeProgram->subdomain ?? '-' }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ $activeProgram->url('/') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-200 text-xs font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition">
                                    Lihat Website
                                </a>
                            </div>
                        </div>

                        <!-- Styling Settings -->
                        <div class="border-t border-gray-100 pt-6 grid grid-cols-3 gap-4 text-center">
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Primary Color</span>
                                <div class="flex items-center justify-center gap-1.5 mt-1">
                                    <div class="w-4 h-4 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $activeProgram->theme->primary_color }}"></div>
                                    <span class="text-xs font-bold text-gray-700">{{ $activeProgram->theme->primary_color }}</span>
                                </div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Secondary Color</span>
                                <div class="flex items-center justify-center gap-1.5 mt-1">
                                    <div class="w-4 h-4 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $activeProgram->theme->secondary_color }}"></div>
                                    <span class="text-xs font-bold text-gray-700">{{ $activeProgram->theme->secondary_color }}</span>
                                </div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Font Heading</span>
                                <span class="text-xs font-bold text-gray-700 mt-1 block">{{ $activeProgram->theme->font_heading }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">Silakan buat atau pilih program terlebih dahulu.</p>
                    @endif
                </div>
            </div>

            <!-- List of Programs Registered -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
                <div>
                    <h3 class="font-bold text-lg text-gray-900">Daftar Website Program Terdaftar</h3>
                    <p class="text-xs text-gray-400 mt-1">Kelola sub-site independen di domain platform Anda.</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-left">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Website</th>
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Domain / Subdomain</th>
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($programs as $p)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 text-sm">{{ $p->name }}</div>
                                        <div class="text-xs text-gray-400">Theme: {{ $p->theme->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">
                                        {{ $p->subdomain ? $p->subdomain . '.localhost' : 'None' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 space-x-3 whitespace-nowrap">
                                        <a href="{{ $p->url('/') }}" target="_blank" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-900 hover:underline">
                                            Buka Website
                                        </a>
                                        <a href="{{ route('admin.programs.edit', $p->id) }}" class="inline-flex items-center text-xs font-bold text-gray-600 hover:text-gray-900 hover:underline">
                                            Config
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
