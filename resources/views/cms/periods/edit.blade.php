@extends('layouts.program-admin.app')

@section('header')
<h2 class="font-extrabold text-xl text-gray-900 leading-tight">
    📁 {{ __('Edit Periode Kepengurusan') }}
</h2>
@endsection

@section('content')
<div class="py-12 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('cms.periods.update', $period->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-black text-slate-700 uppercase mb-2">Nama Periode</label>
                <input type="text" name="name" id="name" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('name', $period->name) }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="year_start" class="block text-xs font-black text-slate-700 uppercase mb-2">Tahun Mulai</label>
                    <input type="number" name="year_start" id="year_start" required min="2000" max="2100" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('year_start', $period->year_start) }}">
                </div>
                <div>
                    <label for="year_end" class="block text-xs font-black text-slate-700 uppercase mb-2">Tahun Selesai</label>
                    <input type="number" name="year_end" id="year_end" required min="2000" max="2100" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('year_end', $period->year_end) }}">
                </div>
            </div>

            <div>
                <label for="status" class="block text-xs font-black text-slate-700 uppercase mb-2">Status Periode</label>
                <select name="status" id="status" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white font-bold">
                    <option value="active" {{ $period->status === 'active' ? 'selected' : '' }}>Aktif (Utama)</option>
                    <option value="archived" {{ $period->status === 'archived' ? 'selected' : '' }}>Arsip / Nonaktif</option>
                </select>
                <p class="text-[10px] text-slate-450 mt-1">Mengaktifkan periode ini akan menonaktifkan periode lainnya secara otomatis.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('cms.periods.index') }}" class="px-5 py-2.5 bg-slate-50 text-slate-650 hover:bg-slate-100 rounded-xl text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
