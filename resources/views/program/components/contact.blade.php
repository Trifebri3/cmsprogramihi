@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #FFFFFF;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    
    // Retrieve dynamic contact details
    $email = $program->getContact('email');
    $whatsapp = $program->getContact('whatsapp');
    $phone = $program->getContact('telepon');
    $address = $program->getContact('alamat');
    $maps = $program->getContact('google maps');
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Contact text details -->
        <div class="lg:col-span-6 space-y-8">
            <div class="space-y-3">
                <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wider">
                    Kontak & Hubungan
                </span>
                <h2 class="text-3xl font-black uppercase text-slate-900 tracking-tight">
                    {{ $data['title'] ?? 'Hubungi Kami' }}
                </h2>
                @if(!empty($data['subtitle']))
                    <p class="text-sm text-slate-500 font-semibold">{{ $data['subtitle'] }}</p>
                @endif
            </div>

            <div class="space-y-5">
                @if($email)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#84A98C] shadow-sm border border-slate-100">
                            📧
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block leading-none">Email Resmi</span>
                            <a href="mailto:{{ $email }}" class="text-sm font-extrabold text-slate-800 hover:underline">{{ $email }}</a>
                        </div>
                    </div>
                @endif

                @if($whatsapp)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#84A98C] shadow-sm border border-slate-100">
                            💬
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block leading-none">WhatsApp Hub</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" class="text-sm font-extrabold text-slate-800 hover:underline">{{ $whatsapp }}</a>
                        </div>
                    </div>
                @endif

                @if($phone)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#84A98C] shadow-sm border border-slate-100">
                            📞
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block leading-none">Nomor Telepon</span>
                            <span class="text-sm font-extrabold text-slate-800">{{ $phone }}</span>
                        </div>
                    </div>
                @endif

                @if($address)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#84A98C] shadow-sm border border-slate-100 mt-1 flex-shrink-0">
                            📍
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block leading-none">Alamat Kantor</span>
                            <span class="text-sm font-extrabold text-slate-800 leading-relaxed">{{ $address }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Google Maps view -->
        <div class="lg:col-span-6 w-full aspect-video rounded-[36px] overflow-hidden border border-slate-100 shadow-xl bg-slate-100 relative group">
            @if($maps && (str_contains($maps, 'iframe') || str_contains($maps, 'http')))
                @if(str_contains($maps, 'iframe'))
                    {!! preg_replace('/width="[0-9%]+"/i', 'width="100%"', preg_replace('/height="[0-9]+"/i', 'height="100%"', $maps)) !!}
                @else
                    <iframe class="w-full h-full border-0" src="{{ $maps }}" allowfullscreen="" loading="lazy"></iframe>
                @endif
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-6 text-center">
                    🗺️
                    <span class="text-xs font-semibold mt-2">Peta lokasi belum dihubungkan.</span>
                </div>
            @endif
        </div>
    </div>
</div>
