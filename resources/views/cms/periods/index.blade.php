@extends('layouts.program-admin.app')

@section('header')
<div class="flex justify-between items-center w-full">
    <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
        📁 {{ __('Kelola Tim & Periode Kepengurusan') }}
    </h2>
    <a href="{{ route('cms.periods.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md uppercase tracking-wider transition-transform active:scale-95">
        + Buat Periode Baru
    </a>
</div>
@endsection

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    @if (session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-250 text-emerald-700 rounded-2xl text-sm font-semibold shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    @if($periods->count() > 0)
        <div class="grid grid-cols-1 gap-8">
            @foreach($periods as $period)
                <div class="bg-white border border-gray-150 rounded-3xl shadow-sm overflow-hidden p-8 space-y-6">
                    <!-- Period Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-5">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl font-black text-slate-900">{{ $period->name }}</span>
                            <span class="text-xs font-bold text-slate-400">({{ $period->year_start }} - {{ $period->year_end }})</span>
                            @if($period->status === 'active')
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black border border-emerald-200 uppercase tracking-widest">
                                    Aktif
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('cms.managements.create', ['period_id' => $period->id]) }}" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-bold transition">
                                + Tambah Anggota
                            </a>
                            <a href="{{ route('cms.periods.edit', $period->id) }}" class="px-3 py-2 bg-slate-50 text-slate-650 hover:bg-slate-100 rounded-xl text-xs font-bold transition">
                                Edit Periode
                            </a>
                            <form action="{{ route('cms.periods.destroy', $period->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini beserta semua anggotanya?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Members List -->
                    @php
                        $members = $period->managements;
                    @endphp
                    @if($members->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                            @foreach($members as $member)
                                <div class="bg-slate-50/50 rounded-2xl border border-slate-150 p-5 flex flex-col justify-between items-center text-center relative group">
                                    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white bg-slate-100 shadow-sm relative mb-4">
                                        @if($member->photo_path)
                                            <img src="{{ asset('storage/' . $member->photo_path) }}" class="w-full h-full object-cover" alt="Photo">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-350 text-xs uppercase font-black">
                                                {{ substr($member->name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="font-extrabold text-sm text-slate-800 line-clamp-1">{{ $member->name }}</h4>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wide">
                                            {{ $member->position }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-slate-150 w-full justify-center">
                                        <a href="{{ route('cms.managements.edit', $member->id) }}" class="text-xs text-indigo-600 hover:underline font-bold">Edit</a>
                                        <span class="text-slate-300">|</span>
                                        <form action="{{ route('cms.managements.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:underline font-bold">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-400 text-xs italic text-center py-6">Belum ada anggota terdaftar untuk periode ini.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-gray-150 rounded-3xl py-16 text-center text-slate-450 shadow-sm max-w-md mx-auto">
            📁 <span class="font-bold block mt-2">Belum ada periode kepengurusan.</span> Tambahkan periode pertama Anda sekarang.
        </div>
    @endif
</div>
@endsection
