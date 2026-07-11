@extends('layouts.program-admin.app')

@section('header')
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Pengguna Program') }}
            </h2>
            <a href="{{ route('cms.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                Tambah Pengguna Baru
            </a>
        </div>
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100 text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Akses / Role</th>
                                        <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Program</th>
                                        <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 text-sm">
                                                {{ $user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                                {{ $user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @foreach($user->roles as $role)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                                        {{ ucfirst($role->name) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->program->name ?? 'Global (Super Admin)' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                                <a href="{{ route('cms.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                                                    Edit
                                                </a>
                                                
                                                @if(auth()->id() !== $user->id)
                                                    <form action="{{ route('cms.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold hover:underline">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">Belum ada pengguna terdaftar untuk program ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
