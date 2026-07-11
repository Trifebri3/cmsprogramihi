@extends('layouts.program-admin.app')

@section('header')
<div class="flex justify-between items-center w-full">
    <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
        📄 {{ __('Kelola Berkas Dokumen & LPJ') }}
    </h2>
    <a href="{{ route('cms.documents.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md uppercase tracking-wider transition-transform active:scale-95">
        + Unggah Dokumen Baru
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

    @if($documents->count() > 0)
        <div class="bg-white border border-gray-150 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-150 text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Berkas</th>
                            <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Sifat Akses</th>
                            <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Rilis</th>
                            <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($documents as $doc)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-5">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 rounded-lg bg-rose-50 text-rose-600 flex-shrink-0 mt-0.5">
                                            📄
                                        </div>
                                        <div>
                                            <h3 class="font-extrabold text-slate-800 leading-snug text-sm md:text-base">{{ $doc->title }}</h3>
                                            @if($doc->description)
                                                <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $doc->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap align-middle">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#E8F0E9] text-[#132E1B] border border-[#132E1B]/5 uppercase tracking-wide">
                                        {{ ucfirst($doc->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap align-middle">
                                    @if($doc->status === 'active')
                                        <span class="text-xs font-bold text-emerald-600">Publik (Aktif)</span>
                                    @else
                                        <span class="text-xs font-bold text-slate-400">Draf (Nonaktif)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-xs text-gray-400 font-semibold align-middle">
                                    {{ $doc->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap align-middle text-sm font-bold space-x-2">
                                    <a href="{{ route('cms.documents.edit', $doc->id) }}" class="text-indigo-600 hover:underline">Edit</a>
                                    <span class="text-slate-200">|</span>
                                    <form action="{{ route('cms.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas ini?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white border border-gray-150 rounded-3xl py-16 text-center text-slate-455 shadow-sm max-w-md mx-auto">
            📄 <span class="font-bold block mt-2">Belum ada dokumen publik.</span> Unggah modul atau LPJ program kerja pertama Anda.
        </div>
    @endif
</div>
@endsection
