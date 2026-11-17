@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Tambah Pembayaran SPP</h2>
        <p class="mt-1 text-sm text-gray-600">Tambahkan data pembayaran baru ke Aplikasi.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tambahkan div untuk alert modern di bagian atas form, awalnya hidden -->
    <div id="modern-alert" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="absolute inset-0 bg-black opacity-30"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden">
            <div class="bg-red-50 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800" id="alert-title">Perhatian</h3>
                        <div class="mt-2">
                            <p class="text-sm text-red-700" id="alert-message">Pesan error akan muncul di sini</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="close-alert" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <form action="{{ route('pembayaran.store') }}" method="POST">
        @csrf
        <div class="max-w-2xl space-y-6">
            <div>
                <label for="siswa_search" class="block text-sm font-medium text-gray-700">Siswa</label>
                <div class="relative">
                    <input type="text" 
                           id="siswa_search" 
                           class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('nisn') border-red-500 @enderror"
                           placeholder="Cari siswa berdasarkan NISN atau nama...">
                    <button type="button" id="clear_search" class="absolute right-2 top-3 text-gray-400 hover:text-gray-600" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="nisn" id="nisn_selected">
                    <div id="search_results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                </div>
                <p id="selected_siswa_info" class="mt-2 text-sm text-gray-600 hidden"></p>
                
                @error('nisn')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tgl_bayar" class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                <input type="date" 
                       id="tgl_bayar"
                       name="tgl_bayar" 
                       value="{{ old('tgl_bayar', date('Y-m-d')) }}"
                       class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('tgl_bayar') border-red-500 @enderror"
                       required>
                @error('tgl_bayar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tahun_dibayar" class="block text-sm font-medium text-gray-700">Tahun Dibayar</label>
                <input type="number" 
                       id="tahun_dibayar"
                       name="tahun_dibayar" 
                       value="{{ old('tahun_dibayar', date('Y')) }}"
                       class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-gray-100 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('tahun_dibayar') border-red-500 @enderror"
                       min="2000" 
                       max="2099"
                       readonly>
                @error('tahun_dibayar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="id_spp" class="block text-sm font-medium text-gray-700">SPP</label>
                <select name="id_spp" 
                        id="id_spp" 
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('id_spp') border-red-500 @enderror"
                        required>
                    <option value="">Pilih SPP</option>
                    @foreach($spp as $s)
                        <option value="{{ $s->id_spp }}" 
                                data-nominal="{{ $s->nominal }}"
                                data-tahun="{{ $s->tahun }}" 
                                {{ old('id_spp') == $s->id_spp ? 'selected' : '' }}>
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
                    <input type="number" 
                           name="jumlah_bayar" 
                           id="jumlah_bayar"
                           value="{{ old('jumlah_bayar') }}"
                           class="mt-1 block w-full rounded-md border border-gray-300 pl-10 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('jumlah_bayar') border-red-500 @enderror"
                           min="0"
                           readonly>
                </div>
                @error('jumlah_bayar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('pembayaran.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    console.log('jQuery ready!');
    
    // Data siswa
    const siswaData = [
        @foreach($siswa as $s)
        {
            nisn: "{{ $s->nisn }}",
            nama: "{{ $s->nama }}",
            kelas: "{{ $s->kelas->nama_kelas ?? 'Tidak ada kelas' }}"
        },
        @endforeach
    ];
    
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
        // Reset pilihan SPP
        $('#id_spp').val('');
        
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
    
    // Pencarian siswa
    $('#siswa_search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        if (searchTerm.length < 2) {
            $('#search_results').addClass('hidden');
            return;
        }
        
        // Filter siswa berdasarkan input
        const filteredSiswa = siswaData.filter(siswa => {
            return siswa.nisn.toLowerCase().includes(searchTerm) || 
                   siswa.nama.toLowerCase().includes(searchTerm);
        });
        
        // Tampilkan hasil pencarian
        if (filteredSiswa.length > 0) {
            let resultsHtml = '';
            filteredSiswa.forEach(siswa => {
                resultsHtml += `
                    <div class="p-2 hover:bg-gray-100 cursor-pointer siswa-item" 
                         data-nisn="${siswa.nisn}" 
                         data-nama="${siswa.nama}"
                         data-kelas="${siswa.kelas}">
                        <div class="font-medium">${siswa.nama}</div>
                        <div class="text-sm text-gray-600">NISN: ${siswa.nisn} | Kelas: ${siswa.kelas}</div>
                    </div>
                `;
            });
            
            $('#search_results').html(resultsHtml).removeClass('hidden');
            
            // Event untuk item yang dipilih
            $('.siswa-item').click(function() {
                const nisn = $(this).data('nisn');
                const nama = $(this).data('nama');
                const kelas = $(this).data('kelas');
                
                // Set nilai input dan hidden field
                $('#siswa_search').val(`${nisn} - ${nama}`);
                $('#nisn_selected').val(nisn);
                
                // Tampilkan info siswa yang dipilih
                $('#selected_siswa_info').text(`Kelas: ${kelas}`).removeClass('hidden');
                
                // Sembunyikan hasil pencarian
                $('#search_results').addClass('hidden');
                
                // Tampilkan tombol clear
                $('#clear_search').show();
            });
        } else {
            $('#search_results').html('<div class="p-2 text-gray-500">Tidak ada siswa yang ditemukan</div>').removeClass('hidden');
        }
    });
    
    // Clear pencarian
    $('#clear_search').click(function() {
        $('#siswa_search').val('');
        $('#nisn_selected').val('');
        $('#selected_siswa_info').addClass('hidden');
        $(this).hide();
    });
    
    // Sembunyikan hasil pencarian ketika klik di luar
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#siswa_search, #search_results').length) {
            $('#search_results').addClass('hidden');
        }
    });
    
    // Fungsi untuk menampilkan alert modern
    function showModernAlert(title, message) {
        $('#alert-title').text(title);
        $('#alert-message').text(message);
        $('#modern-alert').removeClass('hidden');
    }
    
    // Tutup alert ketika tombol tutup diklik
    $('#close-alert').click(function() {
        $('#modern-alert').addClass('hidden');
    });
    
    // Validasi form dengan alert modern
    $('form').submit(function(e) {
        if (!$('#nisn_selected').val()) {
            e.preventDefault();
            showModernAlert('Data Tidak Lengkap', 'Silakan pilih siswa terlebih dahulu');
            return false;
        }
        
        if (!$('#id_spp').val()) {
            e.preventDefault();
            showModernAlert('Data Tidak Lengkap', 'Silakan pilih SPP terlebih dahulu');
            return false;
        }
    });
    
    // Fungsi untuk SPP
    $('#id_spp').on('change', function() {
        const nominal = $(this).find(':selected').data('nominal');
        if (nominal) {
            $('#jumlah_bayar').val(nominal);
        } else {
            $('#jumlah_bayar').val('');
        }
    });
    
    // Trigger change event pada load untuk mengisi jumlah bayar jika SPP sudah dipilih
    $('#id_spp').trigger('change');
    
    // Trigger update tahun dibayar pada load
    updateTahunDibayar();
});
</script>
@endpush

@endsection 



















