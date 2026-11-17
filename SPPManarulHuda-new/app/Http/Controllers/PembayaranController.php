<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('level:bendahara');
    }

    public function index(Request $request)
    {
        // Query dasar untuk pembayaran dengan relasi
        $query = Pembayaran::with(['petugas', 'siswa', 'spp']);
        
        // Pencarian berdasarkan nama siswa atau NISN
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('tgl_bayar', '>=', $request->tanggal_mulai);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tgl_bayar', '<=', $request->tanggal_akhir);
        }
        
        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('tgl_bayar', 'desc');
        
        // Pagination
        $pembayaran = $query->paginate(10);
        
        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $siswa = Siswa::all();
        $spp = Spp::all();
        return view('pembayaran.create', compact('siswa', 'spp'));
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $siswa = Siswa::all();
        $spp = Spp::all();
        $petugas = Petugas::all(); // Tambahkan variabel petugas
        
        return view('pembayaran.edit', compact('pembayaran', 'siswa', 'spp', 'petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|exists:siswas,nisn',
            'tgl_bayar' => 'required|date',
            'tahun_dibayar' => 'required|numeric|min:2000|max:2099',
            'id_spp' => 'required|exists:spp,id_spp',
        ]);

        try {
            // Dapatkan atau buat data petugas
            $petugas = Petugas::firstOrCreate(
                ['username' => Auth::user()->username],
                [
                    'password' => Auth::user()->password,
                    'nama_petugas' => Auth::user()->name,
                    'level' => 'admin'
                ]
            );

            // Dapatkan nominal SPP yang dipilih
            $spp = Spp::findOrFail($request->id_spp);
            $jumlah_bayar = $spp->nominal;
            
            // Cek jumlah pembayaran dalam tahun ini
            $pembayaran_count = Pembayaran::where('nisn', $request->nisn)
                ->whereYear('tgl_bayar', Carbon::parse($request->tgl_bayar)->year)
                ->count();
            
            // Jika sudah 12 pembayaran dalam tahun ini, reset dengan menambahkan flag tahun baru
            $tahun_flag = $pembayaran_count >= 12 ? Carbon::parse($request->tgl_bayar)->year + 1 : Carbon::parse($request->tgl_bayar)->year;

            $pembayaran = Pembayaran::create([
                'id_petugas' => $petugas->id_petugas,
                'nisn' => $request->nisn,
                'tgl_bayar' => $request->tgl_bayar,
                'tahun_dibayar' => $tahun_flag, // Gunakan tahun flag untuk reset progress
                'id_spp' => $request->id_spp,
                'jumlah_bayar' => $jumlah_bayar, // Gunakan nominal dari SPP
            ]);

            return redirect()->route('pembayaran.index')
                ->with('success', 'Data pembayaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nisn' => 'required',
            'tgl_bayar' => 'required|date',
            'tahun_dibayar' => 'required|numeric|min:2000|max:2099',
            'id_spp' => 'required',
        ]);

        try {
            $pembayaran = Pembayaran::findOrFail($id);
            
            // Dapatkan nominal SPP yang dipilih
            $spp = Spp::findOrFail($request->id_spp);
            $jumlah_bayar = $spp->nominal;
            
            // Menambahkan jumlah_bayar ke data yang akan diupdate
            // Tidak perlu mengubah id_petugas karena kita mempertahankan petugas yang sama
            $data = $request->all();
            $data['jumlah_bayar'] = $jumlah_bayar; // Gunakan nominal dari SPP

            $pembayaran->update($data);

            return redirect()->route('pembayaran.index')
                ->with('success', 'Pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pembayaran = Pembayaran::findOrFail($id);
            $siswa = $pembayaran->siswa;
            $pembayaran->delete();

            return redirect()->route('pembayaran.index')
                ->with('warning', "Pembayaran untuk siswa {$siswa->nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus pembayaran.');
        }
    }

    public function history($nisn)
    {
        $pembayaran = Pembayaran::with('petugas')
            ->whereHas('siswa', function($query) use ($nisn) {
                $query->where('nisn', $nisn);
            })
            ->orderBy('tgl_bayar', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pembayaran
        ]);
    }
}




















