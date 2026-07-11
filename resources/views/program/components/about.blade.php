@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #FFFFFF;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Left: Text content -->
        <div class="lg:col-span-6 space-y-6">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wider">
                Profil Program
            </span>
            <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-slate-900 leading-tight">
                {{ $data['title'] ?? 'Tentang Kami' }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-sm font-bold theme-primary-text uppercase tracking-widest leading-relaxed">
                    {{ $data['subtitle'] }}
                </p>
            @endif
            @if(!empty($data['desc_short']))
                <p class="text-base sm:text-lg text-slate-700 leading-relaxed font-semibold">
                    {{ $data['desc_short'] }}
                </p>
            @endif
            @if(!empty($data['desc_full']))
                <p class="text-sm sm:text-base text-slate-500 leading-relaxed font-medium">
                    {{ $data['desc_full'] }}
                </p>
            @endif
        </div>

        <!-- Right: Visi & Misi cards -->
        <div class="lg:col-span-6 space-y-6">
            @if(!empty($data['visi']))
                <div class="bg-slate-50/50 p-8 rounded-[28px] border border-slate-100/80 shadow-sm space-y-3 group hover:border-[#84A98C] transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full theme-primary-bg text-white flex items-center justify-center font-bold text-xs">V</span>
                        <h3 class="font-extrabold text-slate-900 uppercase text-lg">Visi Kami</h3>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed font-semibold italic">"{{ $data['visi'] }}"</p>
                </div>
            @endif

            @if(!empty($data['misi']))
                <div class="bg-slate-50/50 p-8 rounded-[28px] border border-slate-100/80 shadow-sm space-y-3 group hover:border-[#84A98C] transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full theme-primary-bg text-white flex items-center justify-center font-bold text-xs">M</span>
                        <h3 class="font-extrabold text-slate-900 uppercase text-lg">Misi Kami</h3>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium whitespace-pre-line">{{ $data['misi'] }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
