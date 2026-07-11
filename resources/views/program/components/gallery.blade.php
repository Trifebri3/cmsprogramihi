@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #F9FAFB;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    
    // Fetch albums for the program
    $albums = $program->albums()->with('photos')->take(4)->get();
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} space-y-12" x-data="{ localLightboxOpen: false, localPhoto: '', localCaption: '' }">
        <div class="text-center space-y-2">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wider">
                Visual Dokumentasi
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase tracking-tight">
                {{ $data['title'] ?? 'Galeri Kegiatan' }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-sm text-slate-500 font-semibold">{{ $data['subtitle'] }}</p>
            @endif
            <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
        </div>

        @if($albums->count() > 0)
            <div class="space-y-12">
                @foreach($albums as $album)
                    @if($album->photos->count() > 0)
                        <div class="space-y-6">
                            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                <h3 class="font-extrabold text-slate-900 text-lg uppercase tracking-tight">{{ $album->title }}</h3>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-150 px-3 py-1 rounded-full">
                                    {{ $album->photos->count() }} Foto
                                </span>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                @foreach($album->photos->take(4) as $photo)
                                    <div 
                                        @click="localLightboxOpen = true; localPhoto = '{{ asset('storage/' . $photo->file_path) }}'; localCaption = '{{ $photo->caption ?? '' }}'"
                                        class="group relative aspect-square rounded-[28px] overflow-hidden shadow-sm border border-slate-100 cursor-pointer bg-slate-50 transition-all duration-300 hover:shadow-xl hover:border-slate-200 hover:-translate-y-1"
                                    >
                                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->caption ?? $album->title }}">
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                                            <p class="text-white text-xs font-semibold leading-relaxed line-clamp-3">
                                                {{ $photo->caption ?? 'Klik untuk memperbesar' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="bg-white border border-slate-100 rounded-3xl py-12 text-center text-slate-400 max-w-md mx-auto shadow-sm">
                Belum ada album galeri yang dipublikasikan.
            </div>
        @endif

        <!-- Local Lightbox Modal -->
        <div 
            x-show="localLightboxOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/95 flex items-center justify-center p-4 backdrop-blur-md" 
            style="display: none;"
            @keydown.escape.window="localLightboxOpen = false"
        >
            <div class="absolute top-6 right-6 z-50">
                <button @click="localLightboxOpen = false" class="text-white hover:text-slate-300 p-2.5 bg-white/10 hover:bg-white/20 rounded-full transition focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="max-w-4xl w-full flex flex-col items-center gap-5">
                <img class="max-h-[80vh] max-w-full rounded-2xl object-contain shadow-2xl border border-white/10" :src="localPhoto" alt="Gallery Image">
                <p class="text-white/80 text-sm font-semibold max-w-2xl text-center px-4" x-text="localCaption"></p>
            </div>
        </div>
    </div>
</div>
