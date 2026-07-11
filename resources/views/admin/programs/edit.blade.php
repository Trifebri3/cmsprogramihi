@extends('layouts.super-admin.app')

@section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Program Configuration') }} - {{ $program->name }}
        </h2>
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8 space-y-6">
                
                @if ($errors->any())
                    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-semibold">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.programs.update', $program->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Program / Institusi</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $program->name) }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug URL</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $program->slug) }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                        </div>

                        <div>
                            <label for="subdomain" class="block text-sm font-semibold text-gray-700 mb-2">Subdomain</label>
                            <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain', $program->subdomain) }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                        </div>
                    </div>

                    <div>
                        <label for="custom_domain" class="block text-sm font-semibold text-gray-700 mb-2">Custom Domain (Opsional)</label>
                        <input type="text" name="custom_domain" id="custom_domain" value="{{ old('custom_domain', $program->custom_domain) }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                    </div>

                    <div>
                        <label for="theme_id" class="block text-sm font-semibold text-gray-700 mb-2">Tema Warna Terpasang</label>
                        <select name="theme_id" id="theme_id" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            @foreach($themes as $theme)
                                <option value="{{ $theme->id }}" {{ $program->theme_id == $theme->id ? 'selected' : '' }}>
                                    {{ $theme->name }} (Primary: {{ $theme->primary_color }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="admin_user_id" class="block text-sm font-semibold text-gray-700 mb-2">Penanggung Jawab (Program Admin)</label>
                        <select name="admin_user_id" id="admin_user_id" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="" disabled>Pilih User...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ ($currentAdmin && $currentAdmin->id == $user->id) || old('admin_user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Setiap program wajib dikelola oleh satu orang penanggung jawab (Program Admin).</p>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status Sub-site</label>
                        <select name="status" id="status" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="active" {{ $program->status === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ $program->status === 'inactive' ? 'selected' : '' }}>Non-aktif / Draft</option>
                        </select>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-150 pt-6">
                        <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus sub-site program ini secara permanen?')) document.getElementById('delete-program-form').submit();" class="inline-flex items-center text-xs font-bold text-red-650 hover:text-red-900 transition">
                            Hapus Program
                        </button>
                        
                        <div class="flex gap-4">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-255 text-xs font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-program-form" action="{{ route('admin.programs.destroy', $program->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
@endsection
