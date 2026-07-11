@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #1C3F24;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-white";
    $paddingY = $data['padding_y'] ?? 'py-20';
@endphp

<div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 my-12">
    <div class="rounded-[36px] overflow-hidden relative shadow-xl text-center flex flex-col items-center justify-center p-12 sm:p-20" style="{{ $bgStyle }}; {{ $textColor }}">
        <div class="absolute inset-0 bg-gradient-to-br from-black/25 via-transparent to-black/15"></div>
        <div class="relative z-10 space-y-6 max-w-2xl">
            <h2 class="text-3xl sm:text-5xl font-black uppercase tracking-tight leading-tight">
                {{ $data['title'] ?? 'Mari Buat Perubahan Sekarang!' }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-slate-350 text-sm sm:text-lg leading-relaxed font-semibold max-w-lg mx-auto">
                    {{ $data['subtitle'] }}
                </p>
            @endif
            @if(!empty($data['btn_text']))
                <div class="pt-4">
                    <a href="{{ $data['btn_url'] ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-slate-900 hover:bg-slate-50 font-black rounded-full transition shadow-lg text-xs sm:text-sm uppercase tracking-wider">
                        {{ $data['btn_text'] }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
