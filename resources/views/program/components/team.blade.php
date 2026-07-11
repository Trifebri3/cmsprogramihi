@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #FFFFFF;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    
    // Fetch active period and its team from program context
    $activePeriod = $program->activePeriod()->first();
    $members = $activePeriod ? $activePeriod->managements()->get() : collect();
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} space-y-12">
        <div class="text-center space-y-2">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wider">
                Tim Kami
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase tracking-tight">
                {{ $data['title'] ?? 'Struktur Pengurus' }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-sm text-slate-500 font-semibold">{{ $data['subtitle'] }}</p>
            @endif
            <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
        </div>

        @if($members->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($members as $member)
                    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:border-slate-200 hover:-translate-y-1 group">
                        <div class="aspect-square bg-slate-50 relative overflow-hidden">
                            @if($member->photo_path)
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center theme-primary-bg/5 text-slate-800 transition-colors group-hover:bg-emerald-50">
                                    <svg class="w-20 h-20 text-slate-300 group-hover:text-[#132E1B]/20 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-8 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-extrabold text-lg text-slate-900 leading-snug group-hover:theme-primary-text transition-colors">{{ $member->name }}</h3>
                                <span class="inline-flex mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wide">
                                    {{ $member->position }}
                                </span>
                                @if($member->bio)
                                    <p class="text-xs text-slate-400 mt-4 leading-relaxed font-semibold italic">"{{ $member->bio }}"</p>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-3.5 mt-6 pt-5 border-t border-slate-100">
                                @if($member->linkedin)
                                    <a href="{{ $member->linkedin }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-700 transition">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    </a>
                                @endif
                                @if($member->instagram)
                                    <a href="{{ $member->instagram }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-pink-50 hover:text-pink-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white border border-slate-100 rounded-3xl py-12 text-center text-slate-400 max-w-md mx-auto shadow-sm">
                Belum ada tim pengurus terdaftar untuk periode aktif.
            </div>
        @endif
    </div>
</div>
