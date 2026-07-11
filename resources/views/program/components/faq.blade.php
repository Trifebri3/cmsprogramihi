@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #F9FAFB;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    
    // Default faqs structure
    $faqs = $data['faqs'] ?? [
        ['q' => 'Bagaimana cara mendaftar program?', 'a' => 'Anda dapat mendaftar dengan klik tombol pendaftaran di dashboard.'],
        ['q' => 'Apakah dokumen LPJ bersifat publik?', 'a' => 'Ya, seluruh dokumen LPJ diunggah secara berkala dan dapat diakses terbuka.']
    ];
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-4xl mx-auto px-6 sm:px-8 {{ $paddingY }} space-y-12">
        <div class="text-center space-y-2">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wider">
                Pertanyaan Populer
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase tracking-tight">
                {{ $data['title'] ?? 'Frequently Asked Questions (FAQ)' }}
            </h2>
            <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
        </div>

        <div class="space-y-4" x-data="{ activeFaq: null }">
            @foreach($faqs as $idx => $faq)
                <div class="bg-white rounded-[24px] border border-slate-150 overflow-hidden shadow-sm">
                    <button 
                        @click="activeFaq = (activeFaq === {{ $idx }} ? null : {{ $idx }})"
                        class="w-full px-8 py-5 text-left font-extrabold text-slate-900 hover:bg-slate-50/50 flex justify-between items-center transition"
                    >
                        <span>{{ $faq['q'] }}</span>
                        <span class="text-[#84A98C] transition-transform duration-200" :class="{'rotate-45': activeFaq === {{ $idx }}, 'rotate-0': activeFaq !== {{ $idx }}}">
                            ➕
                        </span>
                    </button>
                    <div 
                        x-show="activeFaq === {{ $idx }}" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="px-8 pb-6 pt-1 text-sm text-slate-500 leading-relaxed font-semibold"
                        style="display: none;"
                    >
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
