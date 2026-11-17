<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('level:bendahara');
    }

    public function index()
    {
        $petugas = Petugas::all();
        return view('petugas.index', compact('petugas'));
    }

    public function create()
    {
        // Redirect ke halaman index karena fitur tambah petugas dihapus
        return redirect()->route('petugas.index')
            ->with('error', 'Fitur tambah petugas tidak tersedia.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:25|unique:petugas',
            'password' => 'required|string|min:6',
            'nama_petugas' => 'required|string|max:35',
            'level' => 'required|in:bendahara,kepala_sekolah,ketua_yayasan'
        ]);

        Petugas::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama_petugas' => $request->nama_petugas,
            'level' => $request->level
        ]);

        return redirect()->route('petugas.index')
            ->with('success', 'Data petugas berhasil ditambahkan');
    }

    public function edit(Petugas $petuga)
    {
        // Redirect ke halaman index karena fitur edit petugas dihapus
        return redirect()->route('petugas.index')
            ->with('error', 'Fitur edit petugas tidak tersedia.');
    }

    public function update(Request $request, Petugas $petuga)
    {
        $request->validate([
            'username' => 'required|string|max:25|unique:petugas,username,' . $petuga->id_petugas . ',id_petugas',
            'password' => 'nullable|string|min:6',
            'nama_petugas' => 'required|string|max:35',
            'level' => 'required|in:bendahara,kepala_sekolah,ketua_yayasan'
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $petuga->update($data);

        return redirect()->route('petugas.index')
            ->with('success', 'Data petugas berhasil diupdate');
    }

    public function destroy(Petugas $petuga)
    {
        // Fungsi destroy tidak digunakan lagi
        return redirect()->route('petugas.index')
            ->with('error', 'Fitur hapus petugas tidak tersedia.');
    }
} 







