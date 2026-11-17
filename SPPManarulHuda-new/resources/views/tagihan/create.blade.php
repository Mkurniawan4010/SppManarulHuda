@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Tagihan</h2>
                    <p class="mt-1 text-sm text-gray-600">Tambah tagihan SPP baru untuk siswa</p>
                </div>

                <form action="{{ route('tagihan.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="siswa_id" class="block text-sm font-medium text-gray-700">Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->nisn }}">{{ $siswa->nisn }} - {{ $siswa->nama }}</option>
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
                                    <option value="{{ $spp->id_spp }}" data-nominal="{{ $spp->nominal }}">{{ $spp->tahun }} - Rp {{ number_format($spp->nominal, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                            @error('spp_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_tagihan" class="block text-sm font-medium text-gray-700">Tanggal Tagihan</label>
                            <input type="date" name="tanggal_tagihan" id="tanggal_tagihan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('tanggal_tagihan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_jatuh_tempo" class="block text-sm font-medium text-gray-700">Tanggal Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('tanggal_jatuh_tempo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                <option value="belum_lunas">Belum Lunas</option>
                                <option value="lunas">Lunas</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_tagihan" class="block text-sm font-medium text-gray-700">Jumlah Tagihan</label>
                            <input type="number" name="jumlah_tagihan" id="jumlah_tagihan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('jumlah_tagihan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('tagihan.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sppSelect = document.getElementById('spp_id');
    const jumlahTagihanInput = document.getElementById('jumlah_tagihan');

    sppSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const nominal = selectedOption.dataset.nominal;
        jumlahTagihanInput.value = nominal || '';
    });
});
</script>
@endpush

@endsection 