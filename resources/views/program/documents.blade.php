@extends('layouts.public.app')

@section('content')
<div class="bg-gradient-to-r from-slate-900 via-[#1C3F24] to-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-[#84A98C]/15 text-[#84A98C] border border-[#84A98C]/20 uppercase tracking-widest">
                File Sharing
            </span>
            <h1 class="text-3xl font-black tracking-tight sm:text-5xl uppercase">Repositori Dokumen</h1>
            <p class="text-slate-300 text-xs sm:text-sm font-medium">Repositori proposal, SOP, TOR, Laporan Pertanggungjawaban (LPJ), dan berkas publik lainnya.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    @if($documents->count() > 0)
        <div class="bg-white border border-slate-100 rounded-[32px] shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-slate-100 text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Nama Dokumen</th>
                            <th scope="col" class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Kategori / Sifat</th>
                            <th scope="col" class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Tanggal Rilis</th>
                            <th scope="col" class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($documents as $doc)
                            <tr class="hover:bg-slate-50/40 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-start gap-4">
                                        <!-- PDF Icon -->
                                        <div class="p-3 rounded-2xl bg-rose-50 text-rose-600 flex-shrink-0 mt-0.5 shadow-sm transition-transform group-hover:scale-105">
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-extrabold text-slate-900 leading-snug text-base md:text-lg group-hover:theme-primary-text transition-colors">{{ $doc->title }}</h3>
                                            @if($doc->description)
                                                <p class="text-xs text-slate-400 mt-1.5 leading-relaxed font-semibold max-w-xl">{{ $doc->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap align-middle">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        {{ ucfirst($doc->category) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-sm text-slate-400 font-semibold align-middle">
                                    {{ $doc->created_at->format('d M Y') }}
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap align-middle text-right">
                                    <a 
                                        href="{{ asset('storage/' . $doc->file_path) }}" 
                                        target="_blank" 
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold rounded-xl theme-primary-btn shadow-md shadow-primary/10 transition-transform active:scale-95"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        Download PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white border border-slate-100 rounded-[32px] py-16 text-center text-slate-400 shadow-sm max-w-xl mx-auto">
            <svg class="w-12 h-12 mx-auto text-slate-350 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Belum ada berkas dokumen yang diunggah.
        </div>
    @endif
</div>
@endsection
