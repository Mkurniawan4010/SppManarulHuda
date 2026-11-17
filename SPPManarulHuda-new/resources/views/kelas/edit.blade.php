@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Kelas</h2>
        <p class="mt-1 text-sm text-gray-600">Ubah informasi kelas yang sudah ada dalam Aplikasi.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form action="{{ route('kelas.update', $kelas->id_kelas) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="max-w-2xl space-y-6">
                <div>
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="nama_kelas" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('nama_kelas') border-red-500 @enderror" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                    @error('nama_kelas')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kompetensi_keahlian" class="block text-sm font-medium text-gray-700">Jenjang Pendidikan</label>
                    <input type="text" name="kompetensi_keahlian" id="kompetensi_keahlian" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('kompetensi_keahlian') border-red-500 @enderror" value="{{ old('kompetensi_keahlian', $kelas->kompetensi_keahlian) }}" required>
                    @error('kompetensi_keahlian')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('kelas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 

