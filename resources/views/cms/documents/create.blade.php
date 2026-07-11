@extends('layouts.program-admin.app')

@section('header')
<h2 class="font-extrabold text-xl text-gray-900 leading-tight">
    📄 {{ __('Unggah Berkas Dokumen Baru') }}
</h2>
@endsection

@section('content')
<div class="py-12 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('cms.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-xs font-black text-slate-700 uppercase mb-2">Nama / Judul Dokumen</label>
                <input type="text" name="title" id="title" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="Contoh: Laporan Pertanggungjawaban (LPJ) Kemanusiaan 2026">
            </div>

            <div>
                <label for="description" class="block text-xs font-black text-slate-700 uppercase mb-2">Deskripsi Dokumen</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" placeholder="Jelaskan ringkasan materi atau tujuan berkas dokumen ini..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-black text-slate-700 uppercase mb-2">Sifat / Kategori Akses</label>
                    <select name="category" id="category" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white font-bold">
                        <option value="public" selected>Umum / Public (Muncul di Web)</option>
                        <option value="internal">Khusus / Internal</option>
                        <option value="archive">Arsip / Archived</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-xs font-black text-slate-700 uppercase mb-2">Status Publikasi</label>
                    <select name="status" id="status" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white font-bold">
                        <option value="active" selected>Aktif (Terbit)</option>
                        <option value="inactive">Draf (Sembunyikan)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="document_file" class="block text-xs font-black text-slate-700 uppercase mb-2">Pilih File Dokumen (.pdf, .docx, .xlsx, Max 10MB)</label>
                <input type="file" name="document_file" id="document_file" required class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('cms.documents.index') }}" class="px-5 py-2.5 bg-slate-50 text-slate-650 hover:bg-slate-100 rounded-xl text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    Mulai Unggah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
