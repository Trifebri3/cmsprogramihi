@extends('layouts.program-admin.app')

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
    <div>
        <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
            Kelola Berkas Publikasi dan Konten
        </h2>
        <p class="text-xs text-slate-400 mt-1">Buat, sunting, dan kelola semua artikel, pengumuman, agenda, dan halaman program.</p>
    </div>
    <a href="{{ route('cms.contents.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-sm uppercase tracking-wider transition-transform active:scale-95 shrink-0 flex items-center justify-center">
        Tulis Konten Baru
    </a>
</div>
@endsection

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ searchQuery: '', activeTab: 'all' }">
    @if (session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold shadow-sm flex items-center gap-2">
            <span>Sukses:</span> {{ session('status') }}
        </div>
    @endif

    <!-- Top Stats Counter Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-150 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-xs font-black select-none">TXT</div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Artikel/Berita</span>
                <span class="text-lg font-black text-slate-900">{{ $contents->where('type', 'post')->count() }}</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-150 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 text-xs font-black select-none">DOC</div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Halaman Statis</span>
                <span class="text-lg font-black text-slate-900">{{ $contents->where('type', 'page')->count() }}</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-150 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-xs font-black select-none">INF</div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Pengumuman</span>
                <span class="text-lg font-black text-slate-900">{{ $contents->where('type', 'announcement')->count() }}</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-150 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 text-xs font-black select-none">EVT</div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Agenda/Event</span>
                <span class="text-lg font-black text-slate-900">{{ $contents->where('type', 'event')->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4 bg-white p-4 rounded-3xl border border-slate-150 shadow-sm">
        <!-- Tab Filters -->
        <div class="flex gap-1 overflow-x-auto pb-1 md:pb-0">
            <button type="button" @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                Semua Konten
            </button>
            <button type="button" @click="activeTab = 'post'" :class="activeTab === 'post' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                Post Berita
            </button>
            <button type="button" @click="activeTab = 'page'" :class="activeTab === 'page' ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                Halaman
            </button>
            <button type="button" @click="activeTab = 'announcement'" :class="activeTab === 'announcement' ? 'bg-amber-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                Pengumuman
            </button>
            <button type="button" @click="activeTab = 'event'" :class="activeTab === 'event' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap">
                Agenda
            </button>
        </div>

        <!-- Search Input -->
        <div class="relative max-w-md w-full">
            <input type="text" x-model="searchQuery" placeholder="Cari judul konten..." class="w-full pl-4 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-indigo-500 bg-white placeholder-slate-400">
        </div>
    </div>

    <!-- Cards Grid -->
    @if($contents->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($contents as $content)
                @php
                    // Extract preview body text
                    $preview = '';
                    if (is_array($content->content_blocks)) {
                        foreach ($content->content_blocks as $block) {
                            if (($block['type'] ?? '') === 'text' && !empty($block['body'])) {
                                $preview = strip_tags($block['body']);
                                break;
                            }
                        }
                    }
                    
                    // Style attributes by type
                    $badgeBg = 'bg-slate-100 text-slate-700';
                    if ($content->type === 'post') $badgeBg = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                    elseif ($content->type === 'page') $badgeBg = 'bg-sky-50 text-sky-700 border-sky-200';
                    elseif ($content->type === 'announcement') $badgeBg = 'bg-amber-50 text-amber-700 border-amber-200';
                    elseif ($content->type === 'event') $badgeBg = 'bg-rose-50 text-rose-700 border-rose-200';
                @endphp

                <!-- Content Card Wrapper -->
                <div 
                    x-show="(activeTab === 'all' || activeTab === '{{ $content->type }}') && ('{{ strtolower(addslashes($content->title)) }}'.includes(searchQuery.toLowerCase()))"
                    class="bg-white border border-gray-150 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group"
                >
                    <!-- Card Body -->
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black border uppercase tracking-wider {{ $badgeBg }}">
                                {{ $content->type }}
                            </span>
                            @if($content->status === 'published')
                                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terbit
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-amber-500 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Draf
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1.5">
                            <h3 class="font-extrabold text-slate-900 text-lg leading-snug group-hover:text-indigo-600 transition-colors">
                                @if($content->status === 'published' && $content->program)
                                    <a href="{{ $content->program->url($content->type . '/' . $content->slug) }}" target="_blank">
                                        {{ $content->title }}
                                    </a>
                                @else
                                    {{ $content->title }}
                                @endif
                            </h3>
                            <span class="text-[10px] text-slate-400 font-mono block select-all">Slug: {{ $content->slug }}</span>
                        </div>

                        <p class="text-xs text-slate-400 font-semibold line-clamp-3 leading-relaxed">
                            {{ $preview ?: 'Tidak ada teks isi yang disetel.' }}
                        </p>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center text-xs">
                        <div class="flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-full bg-slate-200 text-[10px] font-black text-slate-655 flex items-center justify-center uppercase">
                                {{ substr($content->author->name ?? 'S', 0, 1) }}
                            </span>
                            <span class="text-slate-450 font-bold">{{ $content->author->name ?? 'System' }}</span>
                        </div>
                        <div class="flex items-center gap-2 font-bold">
                            @can('update', $content)
                                <a href="{{ route('cms.contents.edit', $content->id) }}" class="text-indigo-600 hover:underline">Edit</a>
                            @endcan
                            @can('delete', $content)
                                <span class="text-slate-200">|</span>
                                <form action="{{ route('cms.contents.destroy', $content->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-gray-150 rounded-3xl py-16 text-center text-slate-455 shadow-sm max-w-md mx-auto">
            <span class="font-bold block mt-2">Belum ada konten tulisan.</span> Klik tombol di kanan atas untuk menulis konten pertama Anda.
        </div>
    @endif
</div>
@endsection
