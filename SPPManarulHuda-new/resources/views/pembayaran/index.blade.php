@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Pembayaran</h2>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-2">
            @if(Auth::user()->level === 'bendahara')
            <!-- Tombol Import -->
            <button type="button" onclick="toggleImportModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-file-import mr-2"></i>Import CSV
            </button>
            
            <!-- Tombol Export -->
            <a href="{{ route('export.pembayaran') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-file-export mr-2"></i>Export CSV
            </a>
            @endif
            
            @can('admin-only')
            <a href="{{ route('pembayaran.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-plus mr-2"></i>Tambah Pembayaran
            </a>
            @endcan
        </div>
    </div>

    @include('components.alert')

    <!-- Search Form -->
    <x-search-form route="pembayaran.index" placeholder="Cari berdasarkan nama siswa atau NISN..." />

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-green-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">No</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Petugas</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">NISN</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Siswa</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal Bayar</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">SPP Tahun</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Jumlah Bayar</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Aksi</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($pembayaran as $index => $p)
                <tr class="hover:bg-green-50">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                        {{ ($pembayaran->currentPage() - 1) * $pembayaran->perPage() + $loop->iteration }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $p->petugas->nama_petugas }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $p->siswa->nisn }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $p->siswa->nama }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->tgl_bayar)->translatedFormat('d F Y') }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $p->tahun_dibayar }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <div class="flex justify-end gap-2">
                            @can('admin-only')
                            <form action="{{ route('pembayaran.edit', $p->id_pembayaran) }}" method="GET" class="inline-block">
                                <button type="submit" class="min-w-[80px] text-center inline-flex justify-center items-center px-3 py-1.5 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </button>
                            </form>
                            
                            <form id="delete-form-{{ $p->id_pembayaran }}" action="{{ route('pembayaran.destroy', $p->id_pembayaran) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeletePembayaran('{{ $p->id_pembayaran }}', '{{ $p->siswa->nama }}', '{{ \Carbon\Carbon::parse($p->tgl_bayar)->translatedFormat('d F Y') }}', {{ $p->jumlah_bayar }})" class="min-w-[80px] text-center inline-flex justify-center items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-trash-alt mr-2"></i>Hapus
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-3 py-4 text-sm text-gray-500 text-center">Tidak ada data pembayaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Links -->
    @if($pembayaran instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-4">
        {{ $pembayaran->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection 





<!-- Modal Import -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Import Data Pembayaran</h3>
            <button type="button" onclick="toggleImportModal()" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('import.pembayaran') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File CSV</label>
                <input type="file" name="file" id="file" accept=".csv" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                <div class="mt-2 text-xs text-gray-500">
                    <p class="font-semibold">Format CSV:</p>
                    <p>Baris pertama harus berisi header kolom berikut:</p>
                    <p class="font-mono bg-gray-100 p-1 mt-1">ID Pembayaran,Petugas,NISN,Nama Siswa,Tanggal Bayar,Tahun Dibayar,SPP Tahun,Jumlah Bayar</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="toggleImportModal()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Import
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleImportModal() {
        const modal = document.getElementById('importModal');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
    
    // Validasi form import
    document.addEventListener('DOMContentLoaded', function() {
        const importForm = document.getElementById('importForm');
        if (importForm) {
            importForm.addEventListener('submit', function(e) {
                const fileInput = document.getElementById('file');
                
                // Validasi file dipilih
                if (!fileInput.files || fileInput.files.length === 0) {
                    e.preventDefault();
                    alert('Silakan pilih file CSV terlebih dahulu');
                    return false;
                }
                
                // Validasi ekstensi file
                const fileName = fileInput.value;
                const fileExt = fileName.split('.').pop().toLowerCase();
                
                if (fileExt !== 'csv') {
                    e.preventDefault();
                    alert('Hanya file CSV yang diperbolehkan');
                    return false;
                }
                
                // Tampilkan loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Importing...';
                submitBtn.disabled = true;
                
                return true;
            });
        }
    });
    
    // Existing script for delete confirmation
    function confirmDeletePembayaran(id, nama, tanggal, jumlah) {
        const formattedJumlah = new Intl.NumberFormat('id-ID').format(jumlah);
        const title = 'Hapus Data Pembayaran';
        const text = `Apakah Anda yakin ingin menghapus data pembayaran dari ${nama} pada tanggal ${tanggal} dengan jumlah Rp ${formattedJumlah}?`;
        
        showDeleteConfirmation('delete-form-' + id, title, text);
    }
</script>
@endpush


























