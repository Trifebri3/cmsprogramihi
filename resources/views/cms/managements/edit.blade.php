@extends('layouts.program-admin.app')

@section('header')
<h2 class="font-extrabold text-xl text-gray-900 leading-tight">
    👤 {{ __('Edit Detail Anggota Pengurus') }}
</h2>
@endsection

@section('content')
<div class="py-12 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('cms.managements.update', $management->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-black text-slate-700 uppercase mb-2">Nama Anggota</label>
                <input type="text" name="name" id="name" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('name', $management->name) }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="position" class="block text-xs font-black text-slate-700 uppercase mb-2">Jabatan / Divisi</label>
                    <input type="text" name="position" id="position" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('position', $management->position) }}">
                </div>
                <div>
                    <label for="order_no" class="block text-xs font-black text-slate-700 uppercase mb-2">Urutan Tampil (No. Urut)</label>
                    <input type="number" name="order_no" id="order_no" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('order_no', $management->order_no) }}">
                </div>
            </div>

            <div>
                <label for="bio" class="block text-xs font-black text-slate-700 uppercase mb-2">Biografi Singkat (Bio)</label>
                <textarea name="bio" id="bio" rows="2" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="Quotes atau perkenalan singkat...">{{ old('bio', $management->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="linkedin" class="block text-xs font-black text-slate-700 uppercase mb-2">URL LinkedIn (Opsional)</label>
                    <input type="url" name="linkedin" id="linkedin" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('linkedin', $management->linkedin) }}">
                </div>
                <div>
                    <label for="instagram" class="block text-xs font-black text-slate-700 uppercase mb-2">URL Instagram (Opsional)</label>
                    <input type="url" name="instagram" id="instagram" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('instagram', $management->instagram) }}">
                </div>
            </div>

            <div>
                <label for="photo" class="block text-xs font-black text-slate-700 uppercase mb-2">Ganti Foto Profil (Biarkan kosong jika tetap)</label>
                @if($management->photo_path)
                    <div class="mb-3 w-16 h-16 rounded-full overflow-hidden border border-slate-200 shadow-sm">
                        <img src="{{ asset('storage/' . $management->photo_path) }}" class="w-full h-full object-cover" alt="Current Photo">
                    </div>
                @endif
                <input type="file" name="photo" id="photo" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
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
