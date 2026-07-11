@extends('layouts.public.app')

@section('content')
<div class="bg-gradient-to-r from-slate-900 via-[#1C3F24] to-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-[#84A98C]/15 text-[#84A98C] border border-[#84A98C]/20 uppercase tracking-widest">
                Dokumentasi Aksi
            </span>
            <h1 class="text-3xl font-black tracking-tight sm:text-5xl uppercase">Galeri Kegiatan</h1>
            <p class="text-slate-300 text-xs sm:text-sm font-medium">Jelajahi dokumentasi aksi nyata dan kegiatan kerelawanan di lapangan.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20" x-data="{ lightboxOpen: false, currentPhoto: '', currentCaption: '' }">

    @if($albums->count() > 0)
        <div class="space-y-20">
            @foreach($albums as $album)
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-5 flex justify-between items-end">
                        <div class="space-y-1">
                            <h2 class="text-2xl font-black text-slate-950 uppercase tracking-tight">{{ $album->title }}</h2>
                            @if($album->description)
                                <p class="text-sm text-slate-500 font-semibold">{{ $album->description }}</p>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-3.5 py-1.5 rounded-full shrink-0">
                            {{ $album->photos->count() }} Foto
                        </span>
                    </div>

                    @if($album->photos->count() > 0)
                        <!-- Responsive Photo Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            @foreach($album->photos as $photo)
                                <div 
                                    @click="lightboxOpen = true; currentPhoto = '{{ asset('storage/' . $photo->file_path) }}'; currentCaption = '{{ $photo->caption ?? '' }}'"
                                    class="group relative aspect-square rounded-[28px] overflow-hidden shadow-sm border border-slate-100 cursor-pointer bg-slate-50 transition-all duration-300 hover:shadow-xl hover:border-slate-200 hover:-translate-y-1"
                                >
                                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->caption ?? $album->title }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                                        <p class="text-white text-xs font-semibold leading-relaxed line-clamp-3">
                                            {{ $photo->caption ?? 'Klik untuk memperbesar foto' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-400 text-sm italic font-semibold">Belum ada foto di album ini.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-slate-100 rounded-3xl py-16 text-center text-slate-400 shadow-sm max-w-xl mx-auto">
            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            Belum ada album galeri yang dipublikasikan.
        </div>
    @endif

    <!-- Alpine Lightbox Modal -->
    <div 
        x-show="lightboxOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-250"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/95 flex items-center justify-center p-4 backdrop-blur-md" 
        style="display: none;"
    >
        <div class="absolute top-6 right-6 z-50">
            <button @click="lightboxOpen = false" class="text-white hover:text-slate-300 p-2.5 bg-white/10 hover:bg-white/20 rounded-full transition focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="max-w-4xl w-full flex flex-col items-center gap-5">
            <img class="max-h-[80vh] max-w-full rounded-2xl object-contain shadow-2xl border border-white/10" :src="currentPhoto" alt="Lightbox Image">
            <p class="text-white/80 text-sm font-semibold max-w-2xl text-center px-4" x-text="currentCaption"></p>
        </div>
    </div>

</div>
@endsection
