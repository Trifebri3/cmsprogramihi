@extends('layouts.program-admin.app')

@section('header')
<div class="flex justify-between items-center w-full">
    <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
        🖼️ {{ __('Kelola Galeri & Album Foto') }}
    </h2>
    <a href="{{ route('cms.albums.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md uppercase tracking-wider transition-transform active:scale-95">
        + Buat Album Baru
    </a>
</div>
@endsection

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    @if (session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-250 text-emerald-700 rounded-2xl text-sm font-semibold shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    @if($albums->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($albums as $album)
                <div class="bg-white border border-gray-150 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div class="p-6 space-y-4">
                        <div class="aspect-video w-full rounded-2xl bg-slate-100 border border-slate-150 flex items-center justify-center text-slate-350 text-3xl select-none">
                            📸
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-black text-slate-900 text-lg leading-snug group-hover:text-indigo-600 transition-colors">
                                <a href="{{ route('cms.albums.show', $album->id) }}">{{ $album->title }}</a>
                            </h3>
                            @if($album->description)
                                <p class="text-xs text-slate-400 font-semibold line-clamp-2">{{ $album->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-4 border-t border-slate-50 flex justify-between items-center bg-slate-50/20">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-full">
                            {{ $album->photos_count }} Foto
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('cms.albums.show', $album->id) }}" class="text-xs font-bold text-indigo-600 hover:underline">Kelola Foto</a>
                            <span class="text-slate-300">|</span>
                            <form action="{{ route('cms.albums.destroy', $album->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus album ini beserta semua fotonya?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline font-bold">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-gray-150 rounded-3xl py-16 text-center text-slate-455 shadow-sm max-w-md mx-auto">
            📸 <span class="font-bold block mt-2">Belum ada album galeri.</span> Buat album pertama Anda untuk mempublikasikan foto kegiatan.
        </div>
    @endif
</div>
@endsection
