@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="sm:flex sm:justify-between sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Petugas</h2>
        </div>
        <!-- Tombol Tambah Petugas dihapus -->
    </div>

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-green-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">No</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Username</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Petugas</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Level</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Aksi</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($petugas as $p)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $loop->iteration }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $p->username }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $p->nama_petugas }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $p->level == 'bendahara' ? 'bg-green-100 text-green-800' : 
                                   ($p->level == 'kepala_sekolah' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ ucwords(str_replace('_', ' ', $p->level)) }}
                            </span>
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <div class="flex justify-end gap-2">
                                <!-- Tombol Edit dan Hapus dihapus -->
                                <!-- Tidak ada tombol aksi yang ditampilkan -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 






