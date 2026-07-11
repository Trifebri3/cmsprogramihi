@extends('layouts.super-admin.app')

@section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Provision New Program Sub-site') }}
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

                <form action="{{ route('admin.programs.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Program / Institusi</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white" placeholder="Contoh: Ashoka Indonesia">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug URL</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white" placeholder="ashoka-id">
                        </div>

                        <div>
                            <label for="subdomain" class="block text-sm font-semibold text-gray-700 mb-2">Subdomain</label>
                            <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white" placeholder="ashoka">
                        </div>
                    </div>

                    <div>
                        <label for="custom_domain" class="block text-sm font-semibold text-gray-700 mb-2">Custom Domain (Opsional)</label>
                        <input type="text" name="custom_domain" id="custom_domain" value="{{ old('custom_domain') }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white" placeholder="ashoka.org">
                    </div>

                    <div>
                        <label for="template_type" class="block text-sm font-semibold text-gray-700 mb-2">Preset Template Website</label>
                        <select name="template_type" id="template_type" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="ngo">NGO Internasional Template (Sage Green & Poppins)</option>
                            <option value="kampus">Kampus / Akademik Template (Navy Blue & Playfair)</option>
                            <option value="csr">Company CSR / Korporat Template (Teal & Montserrat)</option>
                            <option value="custom">Custom (Warna Standard & Menu Kosong)</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Sistem akan secara otomatis membuat skema warna, jenis font Google Fonts, dan menu navigasi default.</p>
                    </div>

                    <!-- Admin User Selection / Creation -->
                    <div x-data="{ adminType: 'existing' }" class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700">Akun Program Admin (Penanggung Jawab)</label>
                        
                        <div class="flex gap-4">
                            <label class="inline-flex items-center text-xs font-semibold cursor-pointer">
                                <input type="radio" name="admin_user_type" value="existing" x-model="adminType" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-slate-700">Pilih User Terdaftar</span>
                            </label>
                            <label class="inline-flex items-center text-xs font-semibold cursor-pointer">
                                <input type="radio" name="admin_user_type" value="new" x-model="adminType" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-slate-700">Buat User Baru Sekaligus</span>
                            </label>
                        </div>

                        <!-- Option A: Existing Users List -->
                        <div x-show="adminType === 'existing'" class="space-y-2">
                            <select name="admin_user_id" id="admin_user_id" :required="adminType === 'existing'" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                                <option value="" disabled selected>Pilih User...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('admin_user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400">Setiap program wajib dikelola oleh satu orang penanggung jawab (Program Admin).</p>
                        </div>

                        <!-- Option B: New Admin User Registration Form -->
                        <div x-show="adminType === 'new'" class="space-y-4 p-4 bg-slate-50 border border-slate-150 rounded-2xl" style="display: none;">
                            <div>
                                <label for="admin_name" class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap Admin Baru</label>
                                <input type="text" name="admin_name" id="admin_name" :required="adminType === 'new'" value="{{ old('admin_name') }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white" placeholder="Contoh: Budi Santoso">
                            </div>
                            <div>
                                <label for="admin_email" class="block text-xs font-bold text-gray-600 mb-1">Email Admin Baru</label>
                                <input type="email" name="admin_email" id="admin_email" :required="adminType === 'new'" value="{{ old('admin_email') }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white" placeholder="budi@ashoka.org">
                            </div>
                            <div>
                                <label for="admin_password" class="block text-xs font-bold text-gray-600 mb-1">Password Admin Baru</label>
                                <input type="password" name="admin_password" id="admin_password" :required="adminType === 'new'" class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white" placeholder="Minimal 8 karakter">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status Sub-site</label>
                        <select name="status" id="status" required class="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-aktif / Draft</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-4 border-t border-gray-150 pt-6">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-250 text-xs font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition shadow-sm">
                            Provision Program
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
