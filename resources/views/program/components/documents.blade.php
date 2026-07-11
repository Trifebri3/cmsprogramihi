@php
    $bgStyle = !empty($data['bg_color']) ? "background-color: {$data['bg_color']};" : "background-color: #F9FAFB;";
    $textColor = !empty($data['text_color']) ? "color: {$data['text_color']};" : "text-slate-800";
    $paddingY = $data['padding_y'] ?? 'py-16';
    
    // Fetch public documents
    $documents = $program->documents()
        ->where('status', 'active')
        ->where('category', 'public')
        ->take(5)
        ->get();
@endphp

<div class="border-b border-slate-100 shadow-sm" style="{{ $bgStyle }}; {{ $textColor }}">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 {{ $paddingY }} space-y-12">
        <div class="text-center space-y-2">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wider">
                Dokumen Publik
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase tracking-tight">
                {{ $data['title'] ?? 'Arsip Dokumen' }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-sm text-slate-500 font-semibold">{{ $data['subtitle'] }}</p>
            @endif
            <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
        </div>

        @if($documents->count() > 0)
            <div class="bg-white border border-slate-100 rounded-[32px] shadow-sm overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-slate-100 text-left">
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($documents as $doc)
                                <tr class="hover:bg-slate-50/40 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-start gap-4">
                                            <div class="p-3 rounded-2xl bg-rose-50 text-rose-600 flex-shrink-0 mt-0.5 shadow-sm transition-transform group-hover:scale-105">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                                            </div>
                                            <div>
                                                <h3 class="font-extrabold text-slate-900 leading-snug text-sm md:text-base group-hover:theme-primary-text transition-colors">{{ $doc->title }}</h3>
                                                @if($doc->description)
                                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed font-semibold max-w-xl line-clamp-2">{{ $doc->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-slate-400 font-semibold align-middle">
                                        {{ $doc->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap align-middle text-right">
                                        <a 
                                            href="{{ asset('storage/' . $doc->file_path) }}" 
                                            target="_blank" 
                                            class="inline-flex items-center gap-2 px-5 py-2 text-xs font-bold rounded-xl theme-primary-btn shadow-md shadow-primary/10 transition-transform active:scale-95"
                                        >
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white border border-slate-100 rounded-3xl py-12 text-center text-slate-400 max-w-md mx-auto shadow-sm">
                Belum ada dokumen publik yang dipublikasikan.
            </div>
        @endif
    </div>
</div>
