@extends('layouts.program-admin.app')

@section('header')
<h2 class="font-extrabold text-xl text-gray-900 leading-tight">
    👤 {{ __('Tambah Anggota Pengurus Baru') }} - <span class="text-indigo-600">{{ $period->name }}</span>
</h2>
@endsection

@section('content')
<div class="py-12 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('cms.managements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Hidden period_id -->
            <input type="hidden" name="period_id" value="{{ $period->id }}">

            <div>
                <label for="name" class="block text-xs font-black text-slate-700 uppercase mb-2">Nama Anggota</label>
                <input type="text" name="name" id="name" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="Contoh: Andi Wijaya">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="position" class="block text-xs font-black text-slate-700 uppercase mb-2">Jabatan / Divisi</label>
                    <input type="text" name="position" id="position" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="Contoh: Ketua Umum">
                </div>
                <div>
                    <label for="order_no" class="block text-xs font-black text-slate-700 uppercase mb-2">Urutan Tampil (No. Urut)</label>
                    <input type="number" name="order_no" id="order_no" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="1">
                </div>
            </div>

            <div>
                <label for="bio" class="block text-xs font-black text-slate-700 uppercase mb-2">Biografi Singkat (Bio)</label>
                <textarea name="bio" id="bio" rows="2" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="Quotes atau perkenalan singkat..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="linkedin" class="block text-xs font-black text-slate-700 uppercase mb-2">URL LinkedIn (Opsional)</label>
                    <input type="url" name="linkedin" id="linkedin" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="https://linkedin.com/in/...">
                </div>
                <div>
                    <label for="instagram" class="block text-xs font-black text-slate-700 uppercase mb-2">URL Instagram (Opsional)</label>
                    <input type="url" name="instagram" id="instagram" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="https://instagram.com/...">
                </div>
            </div>

            <div>
                <label for="photo" class="block text-xs font-black text-slate-700 uppercase mb-2">Foto Profil (Rasio 1:1, Max 2MB)</label>
                <input type="file" name="photo" id="photo" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('cms.periods.index') }}" class="px-5 py-2.5 bg-slate-50 text-slate-650 hover:bg-slate-100 rounded-xl text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    Simpan Anggota
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
