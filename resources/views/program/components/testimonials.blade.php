@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #FFFFFF;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    
    // Default testimonials
    $items = $data['items'] ?? [
        ['quote' => 'Kolaborasi bersama Institut Hijau Indonesia membawa perubahan signifikan bagi irigasi sawah kami.', 'author' => 'Pak Budi', 'role' => 'Petani Lokal'],
        ['quote' => 'Kami mendapati pelaporan LPJ sangat transparan dan terbuka.', 'author' => 'Siti Aminah', 'role' => 'Relawan Pendidikan']
    ];
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} space-y-12">
        <div class="text-center space-y-2">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wider">
                Testimoni Relawan
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase tracking-tight">
                {{ $data['title'] ?? 'Suara Sahabat Program' }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-sm text-slate-500 font-semibold">{{ $data['subtitle'] }}</p>
            @endif
            <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($items as $item)
                <div class="bg-slate-50/50 p-8 sm:p-10 rounded-[36px] border border-slate-100 shadow-sm flex flex-col justify-between hover:border-[#84A98C] transition-all duration-300">
                    <div class="space-y-4">
                        <span class="text-4xl font-black theme-primary-text block leading-none">“</span>
                        <p class="text-sm sm:text-base text-slate-700 leading-relaxed font-semibold italic">
                            {{ $item['quote'] ?? '' }}
                        </p>
                    </div>
                    <div class="mt-6 pt-5 border-t border-slate-100 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#84A98C]/25 text-[#132E1B] flex items-center justify-center font-bold text-xs">
                            {{ substr($item['author'] ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-slate-900 block leading-tight">{{ $item['author'] ?? 'User' }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $item['role'] ?? 'Penerima Manfaat' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
