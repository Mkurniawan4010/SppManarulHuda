@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Laporan Pembayaran SPP</h2>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form action="{{ route('laporan.pdf') }}" method="POST" target="_blank">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select name="bulan" id="bulan" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        <option value="">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                    <select name="tahun" id="tahun" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                        @foreach($tahun_spp as $t)
                            <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Tipe Laporan</label>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <input id="tipe_semua" name="tipe_laporan" type="radio" value="semua" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                        <label for="tipe_semua" class="ml-2 block text-sm text-gray-700">
                            Semua Pembayaran
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="tipe_belum_bayar" name="tipe_laporan" type="radio" value="belum_bayar" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <label for="tipe_belum_bayar" class="ml-2 block text-sm text-gray-700">
                            Siswa Belum Bayar
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-file-pdf mr-2"></i> Generate PDF
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


