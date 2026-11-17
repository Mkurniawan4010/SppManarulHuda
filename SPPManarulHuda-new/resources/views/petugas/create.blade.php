<div>
    <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
    <select name="level" id="level" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm @error('level') border-red-500 @enderror" required>
        <option value="">Pilih Level</option>
        <option value="bendahara" {{ old('level') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
        <option value="kepala_sekolah" {{ old('level') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
        <option value="ketua_yayasan" {{ old('level') == 'ketua_yayasan' ? 'selected' : '' }}>Ketua Yayasan</option>
    </select>
    @error('level')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>