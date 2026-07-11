@extends('layouts.public.app')

@section('content')
<section class="py-20 bg-slate-50 min-h-[70vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3.5 py-1.5 rounded-full">
                Kabar Terbaru
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 leading-tight">
                Rilis Warta & Artikel
            </h1>
            <p class="text-sm sm:text-base text-slate-400 font-semibold leading-relaxed">
                Ikuti perkembangan kegiatan, pengumuman, dan artikel terbaru dari program {{ $program->name }}.
            </p>
        </div>

        <!-- Grid of Articles -->
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    @php
                        // Extract cover image and preview body text
                        $coverUrl = null;
                        $preview = '';
                        if (is_array($post->content_blocks)) {
                            foreach ($post->content_blocks as $block) {
                                if (($block['type'] ?? '') === 'hero' && !empty($block['cover_url'])) {
                                    $coverUrl = $block['cover_url'];
                                }
                                if (($block['type'] ?? '') === 'text' && !empty($block['body'])) {
                                    $preview = strip_tags($block['body']);
                                }
                            }
                        }
                    @endphp

                    <div class="bg-white border border-slate-150 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        
                        <!-- Thumbnail banner image -->
                        <div class="aspect-video w-full bg-slate-100 relative overflow-hidden select-none border-b border-slate-100">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="News Cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-350 text-xs font-bold uppercase">
                                    No Image
                                </div>
                            @endif
                        </div>

                        <!-- Card text details -->
                        <div class="p-6 flex-grow space-y-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                                    {{ $post->type }}
                                </span>
                                @foreach($post->categories as $tag)
                                    <span class="text-[9px] font-bold text-slate-400">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>

                            <h3 class="font-extrabold text-slate-900 text-lg leading-snug group-hover:text-indigo-600 transition-colors">
                                <a href="{{ $program->url($post->type . '/' . $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-450 font-semibold line-clamp-3 leading-relaxed">
                                {{ $preview ?: 'Baca selengkapnya mengenai detail artikel ini.' }}
                            </p>
                        </div>

                        <!-- Card metadata footer -->
                        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center text-xs">
                            <span class="text-slate-450 font-semibold">Oleh {{ $post->author->name ?? 'System' }}</span>
                            <span class="text-slate-400 font-bold">{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="pt-8">
                {{ $posts->links() }}
            </div>
        @else
            <div class="py-16 text-center text-slate-400 bg-white border border-slate-150 rounded-3xl max-w-md mx-auto shadow-sm">
                <span class="font-bold block mt-2">Belum ada warta terbaru.</span> Pantau terus halaman kami untuk info mendatang!
            </div>
        @endif

    </div>
</section>
@endsection
