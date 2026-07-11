@extends('layouts.public.app')

@section('content')
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 text-center md:text-left">
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold theme-accent-bg text-black mb-3">
                Kategori Artikel
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl tracking-tight">
                Kategori: <span class="theme-primary-text font-serif italic">{{ $category->name }}</span>
            </h1>
            <p class="text-sm text-gray-400 mt-2">Menampilkan seluruh konten yang diterbitkan dalam kategori ini.</p>
        </div>

        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    @php
                        $bodyText = '';
                        if (is_array($post->content_blocks)) {
                            foreach($post->content_blocks as $block) {
                                if (($block['type'] ?? '') === 'text') {
                                    $bodyText = Str::limit($block['body'] ?? '', 120);
                                }
                            }
                        }
                    @endphp
                    <div class="bg-gray-50/50 rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                        <div class="p-6 space-y-4">
                            <!-- Category tags -->
                            <div class="flex flex-wrap gap-1">
                                @foreach($post->categories as $tag)
                                    <a href="{{ $program->url('category/' . $tag->slug) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold theme-primary-text theme-accent-bg uppercase tracking-wider">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>

                            <h3 class="font-bold text-lg text-gray-900 leading-snug">
                                <a href="{{ $program->url('post/' . $post->slug) }}" class="hover:underline">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            
                            <p class="text-sm text-gray-500 font-normal">
                                {{ $bodyText }}
                            </p>
                        </div>
                        <div class="px-6 py-4 bg-gray-50/20 border-t border-gray-50 flex items-center justify-between">
                            <div class="text-xs text-gray-400">
                                Oleh <span class="font-semibold text-gray-700">{{ $post->author->name }}</span>
                            </div>
                            <span class="text-xs font-bold theme-primary-text">
                                {{ $post->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 p-8 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-gray-500 italic text-sm">Tidak ada artikel di bawah kategori ini saat ini.</p>
                <a href="{{ $program->url('/') }}" class="mt-4 inline-flex items-center text-xs font-bold theme-primary-text hover:underline">
                    ← Kembali ke Home
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
