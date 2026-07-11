@extends('layouts.program-admin.app')

@section('header')
<h2 class="font-extrabold text-xl text-gray-900 leading-tight">
    📄 {{ __('Edit Detail Dokumen') }}
</h2>
@endsection

@section('content')
<div class="py-12 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('cms.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-black text-slate-700 uppercase mb-2">Nama / Judul Dokumen</label>
                <input type="text" name="title" id="title" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white" value="{{ old('title', $document->title) }}">
            </div>

            <div>
                <label for="description" class="block text-xs font-black text-slate-700 uppercase mb-2">Deskripsi Dokumen</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white">{{ old('description', $document->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-black text-slate-700 uppercase mb-2">Sifat / Kategori Akses</label>
                    <select name="category" id="category" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white font-bold">
                        <option value="public" {{ $document->category === 'public' ? 'selected' : '' }}>Umum / Public (Muncul di Web)</option>
                        <option value="internal" {{ $document->category === 'internal' ? 'selected' : '' }}>Khusus / Internal</option>
                        <option value="archive" {{ $document->category === 'archive' ? 'selected' : '' }}>Arsip / Archived</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-xs font-black text-slate-700 uppercase mb-2">Status Publikasi</label>
                    <select name="status" id="status" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 bg-white font-bold">
                        <option value="active" {{ $document->status === 'active' ? 'selected' : '' }}>Aktif (Terbit)</option>
                        <option value="inactive" {{ $document->status === 'inactive' ? 'selected' : '' }}>Draf (Sembunyikan)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="document_file" class="block text-xs font-black text-slate-700 uppercase mb-2">Ganti File Dokumen (Biarkan kosong jika tetap)</label>
                @if($document->file_path)
                    <div class="mb-3 flex items-center gap-2">
                        <span class="text-xs text-slate-400 font-bold bg-slate-100 px-3 py-1.5 rounded-xl block">
                            📄 {{ basename($document->file_path) }}
                        </span>
                    </div>
                @endif
                <input type="file" name="document_file" id="document_file" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('cms.documents.index') }}" class="px-5 py-2.5 bg-slate-50 text-slate-650 hover:bg-slate-100 rounded-xl text-xs font-bold transition">
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
