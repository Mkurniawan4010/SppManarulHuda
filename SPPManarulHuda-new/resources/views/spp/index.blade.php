@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data SPP</h2>
        </div>
        <div class="mt-4 sm:mt-0">
            @can('admin-only')
            <a href="{{ route('spp.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-plus mr-2"></i>Tambah SPP
            </a>
            @endcan
        </div>
    </div>

    @include('components.alert')
    
    <!-- Konten tabel -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-green-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">No</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tahun</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nominal</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Aksi</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($spp as $index => $s)
                    <tr class="hover:bg-green-50">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                            {{ $index + 1 }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $s->tahun }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <div class="flex justify-end space-x-2">
                                @can('admin-only')
                                <a href="{{ route('spp.edit', $s->id_spp) }}" class="inline-flex items-center px-3 py-1.5 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                                
                                <form id="delete-form-{{ $s->id_spp }}" action="{{ route('spp.destroy', $s->id_spp) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteSpp('{{ $s->id_spp }}', '{{ $s->tahun }}', {{ $s->nominal }})" class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-trash-alt mr-2"></i>Hapus
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-4 text-sm text-gray-500 text-center">Tidak ada data SPP</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDeleteSpp(id, tahun, nominal) {
        const formattedNominal = new Intl.NumberFormat('id-ID').format(nominal);
        const title = 'Hapus Data SPP';
        const text = `Apakah Anda yakin ingin menghapus data SPP tahun ${tahun} dengan nominal Rp ${formattedNominal}?`;
        
        showDeleteConfirmation('delete-form-' + id, title, text);
    }
</script>
@endpush






