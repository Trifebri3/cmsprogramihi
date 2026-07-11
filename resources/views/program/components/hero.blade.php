@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-white";
    $paddingY = $data['padding_y'] ?? 'py-24';
    $showBanner = $data['show_banner'] ?? true;
@endphp

<div class="relative min-h-[500px] flex items-center justify-start overflow-hidden shadow-sm" style="{{ $bgStyle }}">
    @if($showBanner && $program->banner_path)
        <img class="absolute inset-0 w-full h-full object-cover opacity-20 scale-102" src="{{ asset('storage/' . $program->banner_path) }}" alt="Hero Banner">
        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/40 to-transparent"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-tr from-slate-950 via-slate-900/60 to-emerald-950/20 opacity-80"></div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 w-full {{ $paddingY }}">
        <div class="max-w-3xl space-y-6">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold theme-accent-bg text-slate-900 uppercase tracking-widest shadow-sm">
                {{ $program->name }}
            </span>
            <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-tight {{ $textColor }} uppercase">
                {{ $data['title'] ?? 'Mewujudkan Dampak Bersama Kami' }}
            </h1>
            <p class="text-slate-300 text-sm sm:text-lg leading-relaxed max-w-2xl font-medium">
                {{ $data['subtitle'] ?? 'Kami merancang dan menjalankan program berkelanjutan untuk kemajuan bersama.' }}
            </p>
            @if(!empty($data['btn_text']))
                <div class="pt-4">
                    <a href="{{ $data['btn_url'] ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 theme-primary-btn font-extrabold rounded-full transition shadow-lg text-xs sm:text-sm uppercase tracking-wider">
                        {{ $data['btn_text'] }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
