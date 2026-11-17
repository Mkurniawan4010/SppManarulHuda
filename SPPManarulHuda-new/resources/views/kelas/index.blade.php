@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="sm:flex sm:justify-between sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Kelas</h2>
        </div>
        <div class="mt-4 sm:mt-0">
            @can('admin-only')
            <a href="{{ route('kelas.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Kelas
            </a>
            @endcan
        </div>
    </div>

    @include('components.alert')

    <!-- Hapus blok alert berikut karena sudah ada di components.alert -->
    {{-- 
    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    --}}

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-green-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">No</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nama Kelas</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Jenjang Pendidikan</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Aksi</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($kelas as $k)
                    <tr class="hover:bg-green-50">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                            {{ $loop->iteration }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $k->nama_kelas }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $k->kompetensi_keahlian }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <div class="flex justify-end gap-2">
                                @can('admin-only')
                                <a href="{{ route('kelas.edit', $k->id_kelas) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                                
                                <form id="delete-form-{{ $k->id_kelas }}" action="{{ route('kelas.destroy', $k->id_kelas) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteKelas('{{ $k->id_kelas }}', '{{ $k->nama_kelas }}', '{{ $k->kompetensi_keahlian }}')" class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-trash-alt mr-2"></i>Hapus
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 





@push('scripts')
<script>
    function confirmDeleteKelas(id, nama, kompetensi) {
        const title = 'Hapus Data Kelas';
        const text = `Apakah Anda yakin ingin menghapus kelas ${nama} - ${kompetensi}?`;
        
        showDeleteConfirmation('delete-form-' + id, title, text);
    }
</script>
@endpush



