@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->nama_petugas }}!</h2>
        <p class="mt-2 text-gray-600">
            @if(Auth::user()->level == 'bendahara')
                Bendahara Dashboard
            @elseif(Auth::user()->level == 'kepala_sekolah')
                Kepala Sekolah Dashboard
            @elseif(Auth::user()->level == 'ketua_yayasan')
                Ketua Yayasan Dashboard
            @endif
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Siswa Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Data Siswa</h3>
                    <a href="{{ route('siswa.index') }}" class="text-blue-500 hover:text-blue-600 text-sm">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Kelas Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <i class="fas fa-school text-green-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Data Kelas</h3>
                    <a href="{{ route('kelas.index') }}" class="text-green-500 hover:text-green-600 text-sm">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        @can('admin-only')
        <!-- SPP Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <i class="fas fa-money-bill text-yellow-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Data SPP</h3>
                    <a href="{{ route('spp.index') }}" class="text-yellow-500 hover:text-yellow-600 text-sm">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Pembayaran Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <i class="fas fa-credit-card text-purple-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Pembayaran</h3>
                    <a href="{{ route('pembayaran.index') }}" class="text-purple-500 hover:text-purple-600 text-sm">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endcan

        @can('bendahara-ketua-yayasan')
        <!-- Laporan SPP Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 mr-4">
                    <i class="fas fa-file-alt text-indigo-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Laporan SPP</h3>
                    <a href="{{ route('laporan.index') }}" class="text-indigo-500 hover:text-indigo-600 text-sm">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endcan
    </div>

    @can('admin-only')
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tombol Pintas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('siswa.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                <i class="fas fa-user-plus text-gray-600 mr-3"></i>
                <span class="text-gray-700">Tambah Siswa Baru</span>
            </a>
            
            <a href="{{ route('pembayaran.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                <i class="fas fa-cash-register text-gray-600 mr-3"></i>
                <span class="text-gray-700">Tambah Pembayaran Baru</span>
            </a>
            
            <a href="{{ route('spp.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                <i class="fas fa-money-bill-wave text-gray-600 mr-3"></i>
                <span class="text-gray-700">Tambah SPP Baru</span>
            </a>
        </div>
    </div>
    @endcan
</div>
@endsection


