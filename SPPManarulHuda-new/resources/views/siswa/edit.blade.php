@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Data Siswa</h2>
        <p class="mt-1 text-sm text-gray-600">Ubah data siswa yang sudah ada dalam Aplikasi.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <form action="{{ route('siswa.update', $siswa->nisn) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div class="mb-4">
                        <label for="nisn" class="block text-sm font-medium text-gray-700">NISN</label>
                        <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $siswa->nisn) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 @error('nisn') border-red-500 @enderror"
                               maxlength="15" 
                               oninput="validateNumeric(this, 'nisn-error')" 
                               placeholder="Masukkan NISN">
                        <span id="nisn-error" class="text-red-500 text-sm hidden">NISN hanya boleh berisi angka</span>
                        @error('nisn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nis" class="block text-sm font-medium text-gray-700">NIS</label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis', $siswa->nis) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 @error('nis') border-red-500 @enderror"
                               maxlength="15" 
                               oninput="validateNumeric(this, 'nis-error')" 
                               placeholder="Masukkan NIS">
                        <span id="nis-error" class="text-red-500 text-sm hidden">NIS hanya boleh berisi angka</span>
                        @error('nis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('nama') border-red-500 @enderror" value="{{ old('nama', $siswa->nama) }}" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="id_kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                        <select name="id_kelas" id="id_kelas" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('id_kelas') border-red-500 @enderror" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kelas')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('alamat') border-red-500 @enderror" required>{{ old('alamat', $siswa->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700">No Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $siswa->no_telepon) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 @error('no_telepon') border-red-500 @enderror"
                               maxlength="15" 
                               oninput="validateNumeric(this, 'no_telepon-error')" 
                               placeholder="Masukkan No Telepon">
                        <span id="no_telepon-error" class="text-red-500 text-sm hidden">No Telepon hanya boleh berisi angka</span>
                        @error('no_telepon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('siswa.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function validateNumeric(input, errorId) {
        const value = input.value;
        const errorElement = document.getElementById(errorId);
        
        // Cek apakah input hanya berisi angka
        if (!/^\d*$/.test(value)) {
            // Jika ada karakter non-angka, tampilkan pesan error
            errorElement.classList.remove('hidden');
        } else {
            // Jika hanya angka, sembunyikan pesan error
            errorElement.classList.add('hidden');
        }
        
        // Batasi panjang input maksimal 15 digit
        if (value.length > 15) {
            input.value = value.slice(0, 15);
        }
    }
</script>
@endpush

