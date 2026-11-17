@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Pembayaran SPP</h2>
        <p class="mt-1 text-sm text-gray-600">Ubah informasi pembayaran SPP yang sudah ada dalam Aplikasi.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('pembayaran.update', $pembayaran->id_pembayaran) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="max-w-2xl space-y-6">
            <!-- Hapus pilihan petugas -->
            
            <div>
                <label for="nisn" class="block text-sm font-medium text-gray-700">Siswa</label>
                <div class="relative">
                    <div class="flex items-center">
                        <input type="text" 
                               id="siswa_search" 
                               class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('nisn') border-red-500 @enderror"
                               placeholder="Cari siswa berdasarkan NISN atau nama..."
                               value="{{ $pembayaran->siswa->nisn }} - {{ $pembayaran->siswa->nama }}"
                               readonly>
                    </div>
                    <input type="hidden" name="nisn" id="nisn_selected" value="{{ old('nisn', $pembayaran->nisn) }}">
                </div>
                <p id="selected_siswa_info" class="mt-2 text-sm text-gray-600">
                    Kelas: {{ $pembayaran->siswa->kelas->nama_kelas ?? 'Tidak ada kelas' }}
                </p>
                <!-- Hapus rekomendasi siswa -->
                @error('nisn')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tgl_bayar" class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                <input type="date" name="tgl_bayar" id="tgl_bayar" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('tgl_bayar') border-red-500 @enderror" value="{{ old('tgl_bayar', $pembayaran->tgl_bayar) }}" required>
                @error('tgl_bayar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tahun_dibayar" class="block text-sm font-medium text-gray-700">Tahun Dibayar</label>
                <input type="number" name="tahun_dibayar" id="tahun_dibayar" min="2000" max="2099" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-gray-100 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('tahun_dibayar') border-red-500 @enderror" value="{{ old('tahun_dibayar', $pembayaran->tahun_dibayar) }}" readonly>
                @error('tahun_dibayar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="id_spp" class="block text-sm font-medium text-gray-700">SPP</label>
                <select name="id_spp" id="id_spp" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('id_spp') border-red-500 @enderror" required>
                    <option value="">Pilih SPP</option>
                    @foreach($spp as $s)
                        <option value="{{ $s->id_spp }}" 
                                data-tahun="{{ $s->tahun }}"
                                {{ old('id_spp', $pembayaran->id_spp) == $s->id_spp ? 'selected' : '' }}>
                            {{ $s->tahun }} - Rp {{ number_format($s->nominal, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('id_spp')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="text" name="jumlah_bayar" id="jumlah_bayar" class="mt-1 block w-full rounded-md border border-gray-300 pl-10 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('jumlah_bayar') border-red-500 @enderror" value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}" readonly>
                </div>
                @error('jumlah_bayar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('pembayaran.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Data SPP
    const sppData = [
        @foreach($spp as $s)
        {
            id: "{{ $s->id_spp }}",
            tahun: "{{ $s->tahun }}",
            nominal: "{{ $s->nominal }}"
        },
        @endforeach
    ];
    
    // Fungsi untuk update tahun dibayar berdasarkan tanggal bayar
    function updateTahunDibayar() {
        const tanggalBayar = $('#tgl_bayar').val();
        if (tanggalBayar) {
            const tahun = new Date(tanggalBayar).getFullYear();
            $('#tahun_dibayar').val(tahun);
            
            // Update SPP berdasarkan tahun
            updateSppByYear(tahun);
        }
    }
    
    // Fungsi untuk update SPP berdasarkan tahun
    function updateSppByYear(tahun) {
        // Cari SPP dengan tahun yang sesuai
        const matchingSpp = sppData.find(spp => spp.tahun == tahun);
        
        if (matchingSpp) {
            // Jika ditemukan, pilih SPP tersebut
            $('#id_spp').val(matchingSpp.id);
        }
        
        // Trigger change event untuk update jumlah bayar
        $('#id_spp').trigger('change');
    }
    
    // Event listener untuk tanggal bayar
    $('#tgl_bayar').on('change', function() {
        updateTahunDibayar();
    });
    
    // Fungsi untuk SPP
    $('#id_spp').on('change', function() {
        const sppId = $(this).val();
        if (sppId) {
            // Ambil nominal SPP dari server
            $.ajax({
                url: '/api/spp/' + sppId,
                type: 'GET',
                success: function(response) {
                    if (response.nominal) {
                        $('#jumlah_bayar').val(response.nominal);
                    }
                },
                error: function() {
                    alert('Gagal mengambil data SPP');
                }
            });
        } else {
            $('#jumlah_bayar').val('');
        }
    });
    
    // Trigger change event pada load
    $('#id_spp').trigger('change');
});
</script>
@endpush

