@extends('layouts.program-admin.app')

@section('header')
<div class="flex justify-between items-center w-full">
    <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
        🖼️ {{ __('Foto Album') }}: <span class="text-indigo-600">{{ $album->title }}</span>
    </h2>
    <a href="{{ route('cms.albums.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
        &larr; Kembali ke Album
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Upload Photo Form (4 columns) -->
        <div class="lg:col-span-4 bg-white border border-gray-150 rounded-3xl p-6 shadow-sm space-y-6">
            <div>
                <h3 class="font-extrabold text-slate-900 uppercase text-sm">Unggah Foto Baru</h3>
                <p class="text-[10px] text-slate-400 mt-1">Tambahkan dokumentasi foto baru ke dalam album ini.</p>
            </div>

            <form action="{{ route('cms.albums.upload-photo', $album->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="photo" class="block text-xs font-bold text-slate-500 mb-2 uppercase">File Foto (Rasio Bebas, Max 3MB)</label>
                    <input type="file" name="photo" id="photo" required class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div>
                    <label for="caption" class="block text-xs font-bold text-slate-500 mb-2 uppercase">Keterangan Foto (Caption)</label>
                    <input type="text" name="caption" id="caption" class="w-full rounded-xl border-gray-200 text-xs bg-white" placeholder="Tulis aktivitas di foto...">
                </div>
                <button type="submit" class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    Unggah & Tempel Foto
                </button>
            </form>
        </div>

        <!-- Right: Photos Grid (8 columns) -->
        <div class="lg:col-span-8 bg-white border border-gray-150 rounded-3xl p-8 shadow-sm space-y-6">
            <div>
                <h3 class="font-extrabold text-slate-900 uppercase text-sm">Koleksi Foto</h3>
                <p class="text-[10px] text-slate-400 mt-1">Daftar foto yang dipublikasikan dalam album ini.</p>
            </div>

            @if($photos->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                    @foreach($photos as $photo)
                        <div class="group relative aspect-square rounded-[24px] overflow-hidden border border-slate-150 shadow-sm bg-slate-50">
                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-full h-full object-cover" alt="Gallery Photo">
                            
                            <!-- Delete overlay button on hover -->
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-4 z-10">
                                <p class="text-white text-[10px] font-semibold line-clamp-3 leading-snug">
                                    {{ $photo->caption ?? 'Tanpa keterangan' }}
                                </p>
                                <form action="{{ route('cms.albums.delete-photo', $photo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?')" class="w-full text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-[9px] uppercase tracking-wider transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center text-slate-400 border border-dashed border-slate-200 rounded-[24px]">
                    Belum ada foto di album ini. Unggah menggunakan panel di sebelah kiri.
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
