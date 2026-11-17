<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class KelasController extends Controller
{
    public function index()
    {
        // Mengambil semua kelas dan mengurutkannya berdasarkan nama_kelas
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'kompetensi_keahlian' => 'required'
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'kompetensi_keahlian' => 'required'
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        // Cek apakah ada siswa yang terkait dengan kelas ini
        $siswaCount = Siswa::where('id_kelas', $id)->count();
        
        if ($siswaCount > 0) {
            return redirect()->route('kelas.index')
                ->with('error', 'Tidak dapat menghapus kelas karena masih ada ' . $siswaCount . ' siswa yang terdaftar di kelas ini.');
        }

        $kelas->delete();

        return redirect()->route('kelas.index')
            ->with('warning', 'Data kelas berhasil dihapus.');
    }
} 


