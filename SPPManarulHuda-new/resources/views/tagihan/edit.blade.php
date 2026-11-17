@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Edit Tagihan</h2>
                    <p class="mt-1 text-sm text-gray-600">Edit tagihan SPP untuk siswa</p>
                </div>

                <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="siswa_id" class="block text-sm font-medium text-gray-700">Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->id }}" {{ $tagihan->siswa_id == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nisn }} - {{ $siswa->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="spp_id" class="block text-sm font-medium text-gray-700">SPP</label>
                            <select name="spp_id" id="spp_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                <option value="">Pilih SPP</option>
                                @foreach($spps as $spp)
                                    <option value="{{ $spp->id }}" {{ $tagihan->spp_id == $spp->id ? 'selected' : '' }}>
                                        {{ $spp->tahun }} - Rp {{ number_format($spp->nominal, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('spp_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                            <select name="bulan" id="bulan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                <option value="">Pilih Bulan</option>
                                <option value="Januari" {{ $tagihan->bulan == 'Januari' ? 'selected' : '' }}>Januari</option>
                                <option value="Februari" {{ $tagihan->bulan == 'Februari' ? 'selected' : '' }}>Februari</option>
                                <option value="Maret" {{ $tagihan->bulan == 'Maret' ? 'selected' : '' }}>Maret</option>
                                <option value="April" {{ $tagihan->bulan == 'April' ? 'selected' : '' }}>April</option>
                                <option value="Mei" {{ $tagihan->bulan == 'Mei' ? 'selected' : '' }}>Mei</option>
                                <option value="Juni" {{ $tagihan->bulan == 'Juni' ? 'selected' : '' }}>Juni</option>
                                <option value="Juli" {{ $tagihan->bulan == 'Juli' ? 'selected' : '' }}>Juli</option>
                                <option value="Agustus" {{ $tagihan->bulan == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                                <option value="September" {{ $tagihan->bulan == 'September' ? 'selected' : '' }}>September</option>
                                <option value="Oktober" {{ $tagihan->bulan == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                                <option value="November" {{ $tagihan->bulan == 'November' ? 'selected' : '' }}>November</option>
                                <option value="Desember" {{ $tagihan->bulan == 'Desember' ? 'selected' : '' }}>Desember</option>
                            </select>
                            @error('bulan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                            <input type="number" name="tahun" id="tahun" value="{{ $tagihan->tahun }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('tahun')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_tagihan" class="block text-sm font-medium text-gray-700">Jumlah Tagihan</label>
                            <input type="number" name="jumlah_tagihan" id="jumlah_tagihan" value="{{ $tagihan->jumlah_tagihan }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('jumlah_tagihan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_jatuh_tempo" class="block text-sm font-medium text-gray-700">Tanggal Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="{{ $tagihan->tanggal_jatuh_tempo }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('tanggal_jatuh_tempo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ $tagihan->keterangan }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('tagihan.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 