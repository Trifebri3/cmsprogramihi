@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #F9FAFB;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    $stats = $data['items'] ?? [];
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} space-y-12">
        @if(!empty($data['title']))
            <div class="text-center">
                <h2 class="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">{{ $data['title'] }}</h2>
                <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($stats as $item)
                <div class="bg-white p-8 sm:p-10 rounded-[36px] border border-slate-100 shadow-sm flex flex-col justify-between hover:border-[#84A98C] transition-all duration-300 group">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="text-4xl sm:text-5xl font-black theme-primary-text tracking-tight">
                                {{ $item['number'] ?? '0' }}
                            </div>
                            <span class="w-9 h-9 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-slate-950 transition-colors font-bold text-sm">
                                &nearr;
                            </span>
                        </div>
                        <h3 class="font-extrabold text-slate-850 text-base leading-tight">{{ $item['label'] ?? '' }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
