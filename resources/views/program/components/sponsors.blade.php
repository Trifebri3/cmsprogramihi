@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #F9FAFB;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    
    // Default logo handles
    $logos = $data['logos'] ?? ['Mitra Utama', 'Sponsor Emas', 'Kolaborator Hijau', 'Donatur Bersama'];
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} space-y-12">
        <div class="text-center space-y-2">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 uppercase tracking-tight">
                {{ $data['title'] ?? 'Sponsor & Mitra Kerja' }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-sm text-slate-500 font-semibold">{{ $data['subtitle'] }}</p>
            @endif
            <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16">
            @foreach($logos as $logo)
                <div class="px-8 py-5 rounded-[24px] border border-slate-150 bg-white shadow-sm flex items-center justify-center font-black text-slate-400 text-sm tracking-widest uppercase select-none hover:text-slate-900 hover:border-slate-200 transition-colors duration-300">
                    🤝 {{ $logo }}
                </div>
            @endforeach
        </div>
    </div>
</div>
