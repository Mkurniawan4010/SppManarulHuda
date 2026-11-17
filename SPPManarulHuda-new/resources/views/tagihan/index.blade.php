@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="sm:flex sm:justify-between sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Tagihan SPP</h2>
        </div>
        <!-- Tombol Tambah Tagihan dihapus -->
    </div>

    <!-- Search Form -->
    <x-search-form route="tagihan.index" placeholder="Cari berdasarkan nama siswa atau NISN..." />

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-green-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">No</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">NISN</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Siswa</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kelas</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status Bulan Ini</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Progress</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Pembayaran</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($data as $index => $item)
                <tr class="hover:bg-green-50">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $item['siswa']->nisn }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $item['siswa']->nama }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $item['siswa']->kelas->nama_kelas }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['status_bulan_ini'] == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $item['status_bulan_ini'] }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($item['progress_pembayaran'] / 12) * 100 }}%"></div>
                        </div>
                        <span class="text-xs mt-1 block">{{ $item['progress_pembayaran'] }}/12 bulan</span>
                        @if($item['progress_pembayaran'] == 12)
                            <span class="text-xs mt-1 block text-green-600 font-semibold">Pembayaran Lengkap</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Rp {{ number_format($item['total_pembayaran'], 0, ',', '.') }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $item['keterangan'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-3 py-4 text-sm text-gray-500 text-center">Tidak ada data tagihan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Links -->
    @if(isset($data) && $data instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-4">
        {{ $data->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection 










