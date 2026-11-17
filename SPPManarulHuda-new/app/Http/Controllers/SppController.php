<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use Illuminate\Http\Request;

class SppController extends Controller
{
    public function __construct()
    {
        $this->middleware('level:bendahara');
    }

    public function index()
    {
        $spp = Spp::all();
        return view('spp.index', compact('spp'));
    }

    public function create()
    {
        return view('spp.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric|digits:4',
            'nominal' => 'required|numeric|min:1000'
        ]);

        Spp::create($request->all());

        return redirect()->route('spp.index')
            ->with('success', 'Data SPP berhasil ditambahkan!');
    }

    public function edit(Spp $spp)
    {
        return view('spp.edit', compact('spp'));
    }

    public function update(Request $request, Spp $spp)
    {
        $request->validate([
            'tahun' => 'required|numeric|digits:4',
            'nominal' => 'required|numeric|min:1000'
        ]);

        $spp->update($request->all());

        return redirect()->route('spp.index')
            ->with('success', 'Data SPP berhasil diupdate');
    }

    public function destroy(Spp $spp)
    {
        try {
            // Simpan informasi SPP untuk pesan
            $tahun = $spp->tahun;
            $nominal = number_format($spp->nominal, 0, ',', '.');
            
            // Cek apakah SPP digunakan dalam pembayaran
            $pembayaranCount = \App\Models\Pembayaran::where('id_spp', $spp->id_spp)->count();
            
            if ($pembayaranCount > 0) {
                return redirect()->route('spp.index')
                    ->with('error', "Tidak dapat menghapus SPP tahun $tahun karena masih digunakan dalam $pembayaranCount data pembayaran.");
            }
            
            // Hapus SPP
            $spp->delete();

            return redirect()->route('spp.index')
                ->with('warning', "Data SPP tahun $tahun dengan nominal Rp $nominal berhasil dihapus");
        } catch (\Exception $e) {
            return redirect()->route('spp.index')
                ->with('error', 'Gagal menghapus data SPP. Error: ' . $e->getMessage());
        }
    }
} 





