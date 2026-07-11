@extends('layouts.program-admin.app')

@section('header')
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Program Admin') }} - {{ $program->name }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ $program->url('/') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-200 text-xs font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition">
                    Lihat Web Publik
                </a>
            </div>
        </div>
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Stats grid -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
                <!-- Articles Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 text-center space-y-2">
                    <span class="text-3xl font-extrabold text-gray-900 block">{{ $totalPosts }}</span>
                    <span class="text-xs text-gray-400 font-semibold uppercase block">Berita/Artikel</span>
                </div>
                <!-- Pages Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 text-center space-y-2">
                    <span class="text-3xl font-extrabold text-gray-900 block">{{ $totalPages }}</span>
                    <span class="text-xs text-gray-400 font-semibold uppercase block">Halaman Statis</span>
                </div>
                <!-- Announcements Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 text-center space-y-2">
                    <span class="text-3xl font-extrabold text-gray-900 block">{{ $totalAnnouncements }}</span>
                    <span class="text-xs text-gray-400 font-semibold uppercase block">Pengumuman</span>
                </div>
                <!-- Events Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 text-center space-y-2">
                    <span class="text-3xl font-extrabold text-gray-900 block">{{ $totalEvents }}</span>
                    <span class="text-xs text-gray-400 font-semibold uppercase block">Kegiatan/Event</span>
                </div>
                <!-- Albums Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 text-center space-y-2">
                    <span class="text-3xl font-extrabold text-gray-900 block">{{ $totalAlbums }}</span>
                    <span class="text-xs text-gray-400 font-semibold uppercase block">Album Galeri</span>
                </div>
                <!-- Documents Count -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 text-center space-y-2">
                    <span class="text-3xl font-extrabold text-gray-900 block">{{ $totalDocs }}</span>
                    <span class="text-xs text-gray-400 font-semibold uppercase block">Dokumen PDF</span>
                </div>
            </div>

            <!-- Management Tools Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Theme styling config detail -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Tema Desain Subsite</h3>
                            <p class="text-xs text-gray-400 mt-1">Branding visual yang aktif saat ini pada domain Anda.</p>
                        </div>
                        <a href="{{ route('cms.theme.edit') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-900 hover:underline">
                            Kustomisasi Tema
                        </a>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                            <span class="text-sm text-gray-500 font-medium">Tema Terpasang</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $program->theme->name }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                            <span class="text-sm text-gray-500 font-medium">Font Header</span>
                            <span class="text-sm font-mono text-gray-800">{{ $program->theme->font_heading }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                            <span class="text-sm text-gray-500 font-medium">Font Body</span>
                            <span class="text-sm font-mono text-gray-800">{{ $program->theme->font_body }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 font-medium">Warna Utama (Primary)</span>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $program->theme->primary_color }}"></div>
                                <span class="text-xs font-mono font-bold text-gray-700">{{ $program->theme->primary_color }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CMS Quick Operations -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Operasi Cepat CMS</h3>
                        <p class="text-xs text-gray-400 mt-1">Kelola konten dan kepengurusan program.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('cms.contents.index') }}" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100/50 transition block">
                            <span class="font-bold text-gray-900 text-sm block">Kelola Artikel</span>
                            <span class="text-xs text-gray-400 mt-1 block">Tulis berita dan update kegiatan.</span>
                        </a>
                        <a href="{{ route('cms.users.index') }}" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100/50 transition block">
                            <span class="font-bold text-gray-900 text-sm block">Kelola Pengguna</span>
                            <span class="text-xs text-gray-400 mt-1 block">Atur hak akses relawan & staf.</span>
                        </a>
                        <a href="{{ route('cms.theme.edit') }}" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100/50 transition block">
                            <span class="font-bold text-gray-900 text-sm block">Kustomisasi Tema</span>
                            <span class="text-xs text-gray-400 mt-1 block">Atur logo, warna, banner, & video.</span>
                        </a>
                        <a href="{{ $program->url('/') }}" target="_blank" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100/50 transition block">
                            <span class="font-bold text-gray-900 text-sm block">Buka Web Publik</span>
                            <span class="text-xs text-gray-400 mt-1 block">Lihat tampilan luar sub-site.</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
