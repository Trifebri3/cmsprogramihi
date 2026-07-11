@extends('layouts.program-admin.app')

@section('header')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
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

                <form action="{{ route('cms.users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Hak Akses / Role</label>
                        <select name="role" id="role" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Gunakan **Editor** untuk akses kelola CMS penuh (tanpa publish), atau **Contributor** untuk membuat draf saja.</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                        <input type="password" name="password" id="password" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                    </div>

                    <div class="flex justify-end gap-4 border-t border-gray-150 pt-6">
                        <a href="{{ route('cms.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-250 text-xs font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition shadow-sm">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
