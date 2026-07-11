@extends('layouts.public.app')

@section('content')
<article class="min-h-[60vh] bg-slate-50 pb-24 font-sans">
    
    <!-- Top Back to News Button -->
    @if($content->type === 'post')
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
            <a href="{{ $program->url('news') }}" class="inline-flex items-center gap-1.5 text-xs font-black text-slate-400 hover:text-indigo-600 transition uppercase tracking-wider">
                &larr; Kembali ke Semua Artikel
            </a>
        </div>
    @endif

    <!-- Check if content blocks exist, otherwise render default simple view -->
    @if(is_array($content->content_blocks) && count($content->content_blocks) > 0)
        
        @foreach($content->content_blocks as $block)
            
            @if(($block['type'] ?? '') === 'hero')
                <!-- Premium Hero Header -->
                @if(!empty($block['cover_url']))
                    <!-- With Cover Image (International Media Style) -->
                    <div class="relative w-full h-[55vh] md:h-[65vh] bg-slate-900 flex items-end">
                        <!-- Cover Background Image -->
                        <div class="absolute inset-0 select-none">
                            <img src="{{ $block['cover_url'] }}" class="w-full h-full object-cover opacity-60" alt="Cover Image">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/30 to-transparent"></div>
                        </div>
                        <!-- Article Meta & Title (Overlayed) -->
                        <div class="relative max-w-4xl mx-auto px-6 sm:px-8 pb-12 w-full text-white space-y-4">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400 text-slate-955 shadow-sm">
                                    {{ ucfirst($content->type) }}
                                </span>
                                @if($content->type === 'post')
                                    @foreach($content->categories as $tag)
                                        <a href="{{ $program->url('category/' . $tag->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/20 hover:bg-white/30 text-white transition backdrop-blur-sm">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight md:max-w-3xl">
                                {{ $block['title'] ?? $content->title }}
                            </h1>
                            <div class="flex items-center gap-3 pt-2 text-xs text-slate-300">
                                <span class="w-6 h-6 rounded-full bg-indigo-600 flex items-center justify-center font-black text-white uppercase text-[10px]">
                                    {{ substr($content->author->name ?? 'S', 0, 1) }}
                                </span>
                                <span>Oleh <strong class="text-white">{{ $content->author->name ?? 'System' }}</strong></span>
                                <span class="text-slate-500">•</span>
                                <span>{{ $content->published_at ? $content->published_at->format('d M Y') : $content->created_at->format('d M Y') }}</span>
                             </div>
                        </div>
                    </div>
                @else
                    <!-- Clean Editorial Style (No Cover) -->
                    <div class="bg-white border-b border-slate-100 py-16 px-6 text-center">
                        <div class="max-w-3xl mx-auto space-y-6">
                            <div class="flex justify-center items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ ucfirst($content->type) }}
                                </span>
                                @if($content->type === 'post')
                                    @foreach($content->categories as $tag)
                                        <a href="{{ $program->url('category/' . $tag->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-slate-900 leading-tight">
                                {{ $block['title'] ?? $content->title }}
                            </h1>
                            <div class="flex items-center justify-center gap-3 pt-2 text-xs text-slate-400">
                                <span class="w-6 h-6 rounded-full bg-indigo-600 flex items-center justify-center font-black text-white uppercase text-[10px]">
                                    {{ substr($content->author->name ?? 'S', 0, 1) }}
                                </span>
                                <span>Oleh <strong class="text-slate-800">{{ $content->author->name ?? 'System' }}</strong></span>
                                <span class="text-slate-300">•</span>
                                <span>{{ $content->published_at ? $content->published_at->format('d M Y') : $content->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

            @elseif(($block['type'] ?? '') === 'text')
                <!-- Rich Text Block -->
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="bg-white p-8 sm:p-12 rounded-3xl border border-gray-150 shadow-sm space-y-6">
                        @if(!empty($block['heading']))
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 border-b border-slate-100 pb-4">
                                {{ $block['heading'] }}
                            </h2>
                        @endif
                        <div class="prose prose-lg prose-slate text-slate-655 leading-relaxed max-w-none">
                            {!! $block['body'] ?? '' !!}
                        </div>
                    </div>
                </div>

            @elseif(($block['type'] ?? '') === 'cta')
                <!-- Call To Action Block -->
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="theme-primary-bg rounded-3xl p-8 sm:p-12 text-center text-white shadow-md relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent pointer-events-none"></div>
                        <div class="relative z-10 space-y-6">
                            <h2 class="text-2xl sm:text-4xl font-extrabold leading-tight">
                                {{ $block['title'] ?? 'Dukung Gerakan Kami' }}
                            </h2>
                            @if(!empty($block['subtitle']))
                                <p class="text-white/80 text-sm sm:text-base max-w-xl mx-auto">
                                    {{ $block['subtitle'] }}
                                </p>
                            @endif
                            @if(!empty($block['button_text']) && !empty($block['button_url']))
                                <div class="pt-2">
                                    <a href="{{ $block['button_url'] }}" class="inline-flex items-center justify-center px-6 py-3 bg-white theme-primary-text hover:bg-slate-50 text-sm font-bold rounded-xl shadow-sm transition">
                                        {{ $block['button_text'] }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @elseif(($block['type'] ?? '') === 'gallery')
                <!-- Image Grid Block -->
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="bg-white p-8 rounded-3xl border border-gray-150 shadow-sm">
                        @if(!empty($block['title']))
                            <h3 class="text-md font-extrabold text-slate-900 mb-6 border-b border-slate-100 pb-3 uppercase tracking-wider">{{ $block['title'] }}</h3>
                        @endif
                        @if(!empty($block['images']) && is_array($block['images']))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($block['images'] as $imgUrl)
                                    <div class="rounded-2xl overflow-hidden aspect-video bg-slate-50 shadow-sm border border-slate-100 group">
                                        <img src="{{ $imgUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="Documentation Image">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            @elseif(($block['type'] ?? '') === 'video')
                <!-- Dynamic YouTube Embed Block -->
                @php
                    $videoUrl = $block['video_url'] ?? '';
                    $videoId = '';
                    if (preg_match('/(?:youtube\\.com\\/(?:[^\\/]+\\/.+\\/|(?:v|e(?:mbed)?)\\/|.*[?&]v=)|youtu\\.be\\/)([^"&?\\/ ]{11})/i', $videoUrl, $match)) {
                        $videoId = $match[1];
                    }
                @endphp
                @if($videoId)
                    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        <div class="bg-white p-8 rounded-3xl border border-gray-150 shadow-sm space-y-4">
                            @if(!empty($block['title']))
                                <h3 class="text-md font-extrabold text-slate-900 border-b border-slate-100 pb-3 uppercase tracking-wider">{{ $block['title'] }}</h3>
                            @endif
                            <div class="aspect-video rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-slate-50">
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                @endif

            @elseif(($block['type'] ?? '') === 'button')
                <!-- Styled Button Block -->
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center">
                    <a href="{{ $block['button_url'] ?? '#' }}" target="_blank" class="inline-flex items-center justify-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-full transition shadow-lg text-xs uppercase tracking-wider">
                        {{ $block['button_text'] ?? 'Klik di Sini' }}
                    </a>
                </div>
            @endif

        @endforeach

    @else
        <!-- Default Fallback View -->
        <div class="bg-white border-b border-gray-100 py-16 px-6 text-center">
            <div class="max-w-4xl mx-auto space-y-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold theme-accent-bg text-black">
                    {{ ucfirst($content->type) }}
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 leading-tight">
                    {{ $content->title }}
                </h1>
                <p class="text-xs text-gray-400">
                    Diterbitkan {{ $content->published_at ? $content->published_at->format('d M Y') : $content->created_at->format('d M Y') }} oleh {{ $content->author->name }}
                </p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white p-8 sm:p-12 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-gray-600 leading-relaxed">
                    Konten ini belum memiliki susunan tata letak blok builder. Silakan masuk ke panel admin untuk menyusun tata letak.
                </p>
            </div>
        </div>
    @endif

    <!-- Bottom Back to News Button -->
    @if($content->type === 'post')
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 text-center">
            <a href="{{ $program->url('news') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 hover:border-slate-350 text-slate-500 hover:text-slate-800 rounded-full text-xs font-black transition shadow-sm uppercase tracking-wider">
                &larr; Kembali ke Semua Artikel
            </a>
        </div>
    @endif

</article>
@endsection
