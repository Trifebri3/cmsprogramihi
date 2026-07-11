@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #FFFFFF;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    
    // Fetch dynamic content list from program relation
    $posts = $program->contents()
        ->where('type', 'post')
        ->where('status', 'published')
        ->orderBy('published_at', 'desc')
        ->take(3)
        ->get();
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} space-y-12">
        <div class="text-center space-y-2">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wider">
                Artikel Terkini
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase tracking-tight">
                {{ $data['title'] ?? 'Berita & Pengumuman' }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-sm text-slate-500 font-semibold">{{ $data['subtitle'] }}</p>
            @endif
            <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
        </div>

        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <article class="bg-white rounded-[32px] border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:border-slate-200 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                        <div class="p-8 space-y-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#E8F0E9] text-[#132E1B] uppercase tracking-wider">
                                {{ $post->created_at->format('d M Y') }}
                            </span>
                            <h3 class="font-extrabold text-slate-900 text-lg leading-snug group-hover:theme-primary-text transition-colors">
                                <a href="{{ $program->url('post/' . $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            @if($post->content_blocks)
                                @php
                                    $firstBlock = collect($post->content_blocks)->firstWhere('type', 'text');
                                    $plainText = $firstBlock ? strip_tags($firstBlock['body'] ?? '') : '';
                                @endphp
                                <p class="text-xs text-slate-400 leading-relaxed line-clamp-3 font-semibold">
                                    {{ $plainText ?: 'Klik untuk membaca detail tulisan...' }}
                                </p>
                            @endif
                        </div>
                        <div class="px-8 pb-8 pt-4 border-t border-slate-50 flex justify-between items-center bg-slate-50/20">
                            <a href="{{ $program->url($post->type . '/' . $post->slug) }}" class="text-xs font-bold theme-primary-text hover:underline inline-flex items-center gap-1">
                                Baca Selengkapnya &rarr;
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Permanent Link Button to All Articles page -->
            <div class="pt-8 text-center">
                <a href="{{ $program->url('news') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl theme-primary-btn shadow-sm transition">
                    Lihat Semua Artikel &rarr;
                </a>
            </div>
        @else
            <div class="bg-white border border-slate-100 rounded-3xl py-12 text-center text-slate-400 max-w-md mx-auto shadow-sm">
                Belum ada artikel atau kabar yang dirilis.
            </div>
        @endif
    </div>
</div>
