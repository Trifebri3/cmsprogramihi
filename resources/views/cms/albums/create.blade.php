@extends('layouts.program-admin.app')

@section('header')
<h2 class="font-extrabold text-xl text-gray-900 leading-tight">
    📸 {{ __('Buat Album Galeri Baru') }}
</h2>
@endsection

@section('content')
<div class="py-12 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('cms.albums.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-xs font-black text-slate-700 uppercase mb-2">Nama / Judul Album</label>
                <input type="text" name="title" id="title" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="Contoh: Aksi Penanaman Mangrove 2026">
            </div>

            <div>
                <label for="description" class="block text-xs font-black text-slate-700 uppercase mb-2">Deskripsi Album</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="Penjelasan singkat mengenai dokumentasi foto dalam album..."></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('cms.albums.index') }}" class="px-5 py-2.5 bg-slate-50 text-slate-650 hover:bg-slate-100 rounded-xl text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    Buat Album
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
