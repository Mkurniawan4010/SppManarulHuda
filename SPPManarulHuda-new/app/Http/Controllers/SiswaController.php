<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        // Urutkan berdasarkan kelas terkecil terlebih dahulu, kemudian nama siswa
        $query->join('kelas', 'siswas.id_kelas', '=', 'kelas.id_kelas')
              ->orderBy('kelas.nama_kelas', 'asc')
              ->orderBy('siswas.nama', 'asc')
              ->select('siswas.*');

        // Mengubah jumlah siswa per halaman menjadi 10
        $siswa = $query->paginate(10); // 10 siswa per halaman
        
        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:10|unique:siswas|regex:/^[0-9]+$/',
            'nis' => 'required|string|max:12|unique:siswas|regex:/^[0-9]+$/',
            'nama' => 'required|string|max:35',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:13|regex:/^[0-9]+$/'
        ], [
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
            'nis.regex' => 'NIS hanya boleh berisi angka.',
            'no_telepon.regex' => 'Nomor telepon hanya boleh berisi angka.'
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => [
                'required',
                'string',
                'max:10',
                'regex:/^[0-9]+$/',
                Rule::unique('siswas', 'nisn')->ignore($siswa->nisn, 'nisn')
            ],
            'nis' => [
                'required',
                'string',
                'max:8',
                'regex:/^[0-9]+$/',
                Rule::unique('siswas', 'nis')->ignore($siswa->nisn, 'nisn')
            ],
            'nama' => 'required|string|max:35',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:13|regex:/^[0-9]+$/'
        ], [
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
            'nis.regex' => 'NIS hanya boleh berisi angka.',
            'no_telepon.regex' => 'Nomor telepon hanya boleh berisi angka.'
        ]);

        try {
            // Update data siswa - dengan CASCADE UPDATE, data di tabel pembayarans juga akan terupdate
            $siswa->update($request->all());
            
            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Siswa $siswa)
    {
        try {
            // Cek apakah siswa memiliki data pembayaran
            $pembayaranCount = \App\Models\Pembayaran::where('nisn', $siswa->nisn)->count();
            
            // Simpan nama siswa untuk pesan konfirmasi
            $namaSiswa = $siswa->nama;
            
            // Hapus siswa (cascade delete akan menghapus pembayaran terkait)
            $siswa->delete();
            
            if ($pembayaranCount > 0) {
                return redirect()->route('siswa.index')
                    ->with('warning', "Data siswa $namaSiswa berhasil dihapus beserta $pembayaranCount data pembayaran terkait.");
            } else {
                return redirect()->route('siswa.index')
                    ->with('success', "Data siswa $namaSiswa berhasil dihapus.");
            }
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')
                ->with('error', "Gagal menghapus data siswa: " . $e->getMessage());
        }
    }
} 














