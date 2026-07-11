@extends('layouts.public.app')

@section('content')
<div class="bg-gradient-to-r from-slate-900 via-[#1C3F24] to-slate-950 text-white py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">
            <div class="space-y-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-[#84A98C]/15 text-[#84A98C] border border-[#84A98C]/20 uppercase tracking-widest">
                    Struktur Kepengurusan
                </span>
                <h1 class="text-3xl font-black tracking-tight sm:text-5xl uppercase">Struktur Pengurus</h1>
                <p class="text-slate-300 text-xs sm:text-sm font-medium">Daftar pengurus aktif dan arsip pengurus periode sebelumnya.</p>
            </div>
            
            <!-- Period Selector -->
            @if($periods->count() > 0)
                <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 bg-white/5 p-2 rounded-2xl border border-white/10 backdrop-blur-md">
                    <label for="period_id" class="text-xs font-bold text-slate-350 whitespace-nowrap pl-2 uppercase tracking-wider">Periode:</label>
                    <select name="period_id" id="period_id" onchange="this.form.submit()" class="rounded-xl border-0 text-slate-800 text-sm focus:ring-2 focus:ring-emerald-500 shadow-sm bg-white min-w-[200px] font-bold">
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ $currentPeriod && $currentPeriod->id == $period->id ? 'selected' : '' }}>
                                {{ $period->name }} ({{ $period->year_start }} - {{ $period->year_end }})
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    @if($currentPeriod)
        <div class="text-center mb-16 space-y-2">
            <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold theme-accent-bg text-slate-900 shadow-sm uppercase tracking-wider">
                Periode Terpilih
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 uppercase tracking-tight">{{ $currentPeriod->name }}</h2>
            <p class="text-sm text-slate-500 font-semibold">Masa Bhakti {{ $currentPeriod->year_start }} s.d. {{ $currentPeriod->year_end }}</p>
            <div class="w-12 h-1 bg-[#84A98C] mx-auto rounded-full mt-4"></div>
        </div>

        @if($managements->count() > 0)
            <!-- Committee Board Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($managements as $member)
                    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:border-slate-200 hover:-translate-y-1 group">
                        <div class="aspect-square bg-slate-50 relative overflow-hidden">
                            @if($member->photo_path)
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}">
                            @else
                                <!-- User Icon Fallback -->
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
                            
                            <!-- Social Medias -->
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
            <div class="bg-white border border-slate-100 rounded-3xl py-16 text-center text-slate-400 shadow-sm max-w-xl mx-auto">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                Belum ada pengurus terdaftar untuk periode ini.
            </div>
        @endif
    @else
        <div class="bg-white border border-slate-100 rounded-3xl py-16 text-center text-slate-400 shadow-sm max-w-xl mx-auto">
            Belum ada periode kepengurusan yang dikonfigurasi.
        </div>
    @endif
</div>
@endsection
