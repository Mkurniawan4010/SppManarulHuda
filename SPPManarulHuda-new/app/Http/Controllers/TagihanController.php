<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\SPP;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua siswa dengan relasi pembayaran dan kelas
        $query = Siswa::with(['pembayaran', 'kelas']);
        
        // Pencarian berdasarkan nama atau NISN
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }
        
        // Urutkan berdasarkan kelas terkecil terlebih dahulu, kemudian nama siswa
        $query->join('kelas', 'siswas.id_kelas', '=', 'kelas.id_kelas')
              ->orderBy('kelas.nama_kelas', 'asc')
              ->orderBy('siswas.nama', 'asc')
              ->select('siswas.*');
        
        // Ambil siswa dengan pagination
        $siswa = $query->paginate(10);
        
        // Tanggal saat ini
        $current_date = Carbon::now();
        $current_month = $current_date->month;
        $current_year = $current_date->year;
        
        // Mapping data siswa dengan status pembayaran
        $data = $siswa->map(function ($siswa) use ($current_month, $current_year) {
            // Cek pembayaran bulan ini
            $pembayaran_bulan_ini = $siswa->pembayaran()
                ->whereYear('tgl_bayar', $current_year)
                ->whereMonth('tgl_bayar', $current_month)
                ->where('nisn', $siswa->nisn)
                ->exists();
            
            // Cari tahun pembayaran terbaru untuk siswa ini
            $tahun_terbaru = $siswa->pembayaran()
                ->where('nisn', $siswa->nisn)
                ->orderBy('tahun_dibayar', 'desc')
                ->value('tahun_dibayar');
                
            // Jika tidak ada pembayaran, gunakan tahun saat ini
            $tahun_terbaru = $tahun_terbaru ?? $current_year;
            
            // Inisialisasi bulan terbayar ke 0 jika tidak ada pembayaran
            $bulan_terbayar = 0;
            $total_pembayaran = 0;
            
            // Hanya hitung jika siswa memiliki pembayaran
            if ($siswa->pembayaran->count() > 0) {
                // Hitung jumlah bulan yang sudah dibayar untuk tahun terbaru
                $pembayaran_tahun_terbaru = $siswa->pembayaran()
                    ->where('tahun_dibayar', $tahun_terbaru)
                    ->where('nisn', $siswa->nisn)
                    ->get();
                    
                // Ekstrak bulan unik dari pembayaran
                $bulan_unik = $pembayaran_tahun_terbaru->map(function($pembayaran) {
                    return Carbon::parse($pembayaran->tgl_bayar)->month;
                })->unique()->values();
                
                $bulan_terbayar = $bulan_unik->count();
                
                // Jika bulan terbayar melebihi 12, reset ke 12 (maksimal 12 bulan)
                $bulan_terbayar = min($bulan_terbayar, 12);
                
                // Hitung total pembayaran hanya untuk tahun terbaru dan siswa ini
                $total_pembayaran = $siswa->pembayaran()
                    ->where('tahun_dibayar', $tahun_terbaru)
                    ->where('nisn', $siswa->nisn)
                    ->sum('jumlah_bayar');
            }
            
            return [
                'siswa' => $siswa,
                'status_bulan_ini' => $pembayaran_bulan_ini ? 'Lunas' : 'Belum Lunas',
                'total_pembayaran' => $total_pembayaran,
                'progress_pembayaran' => $bulan_terbayar,
                'sisa_bulan' => 12 - $bulan_terbayar,
                'keterangan' => $pembayaran_bulan_ini ? 'Sudah bayar bulan ' . Carbon::now()->translatedFormat('F Y') : 'Belum bayar bulan ' . Carbon::now()->translatedFormat('F Y')
            ];
        });

        // Buat custom paginator untuk data yang sudah di-map
        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $data,
            $siswa->total(),
            $siswa->perPage(),
            $siswa->currentPage(),
            ['path' => \Request::url(), 'query' => $request->query()]
        );

        return view('tagihan.index', compact('data', 'current_month', 'current_year'));
    }

    // Hapus atau komentar method create dan store karena tidak diperlukan lagi
    /*
    public function create()
    {
        $siswas = Siswa::all();
        $spps = SPP::orderBy('tahun', 'desc')->get(); // Urutkan SPP berdasarkan tahun terbaru
        return view('tagihan.create', compact('siswas', 'spps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,nisn',
            'spp_id' => 'required|exists:spp,id_spp',
            'bulan' => 'required|string',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        // Ambil data SPP yang dipilih
        $spp = SPP::findOrFail($request->spp_id);

        // Cek pembayaran terakhir siswa
        $pembayaranTerakhir = Pembayaran::where('nisn', $request->siswa_id)
            ->latest('tgl_bayar')
            ->first();

        // Set status berdasarkan pembayaran terakhir
        $status = $pembayaranTerakhir && $pembayaranTerakhir->jumlah_bayar >= $request->jumlah_tagihan 
            ? 'lunas' 
            : 'belum_lunas';

        $tagihan = Tagihan::create([
            'siswa_id' => $request->siswa_id,
            'spp_id' => $request->spp_id,
            'bulan' => $request->bulan,
            'tahun' => $spp->tahun, // Gunakan tahun dari SPP yang dipilih
            'jumlah_tagihan' => $request->jumlah_tagihan,
            'status' => $status,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan berhasil ditambahkan');
    }
    */

    public function edit(Tagihan $tagihan)
    {
        $siswas = Siswa::all();
        $spps = SPP::all();
        return view('tagihan.edit', compact('tagihan', 'siswas', 'spps'));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,nisn',
            'spp_id' => 'required|exists:spp,id_spp',
            'bulan' => 'required|string',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,belum_lunas',
            'tanggal_jatuh_tempo' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        // Ambil data SPP yang dipilih
        $spp = SPP::findOrFail($request->spp_id);

        $tagihan->update([
            'siswa_id' => $request->siswa_id,
            'spp_id' => $request->spp_id,
            'bulan' => $request->bulan,
            'tahun' => $spp->tahun, // Gunakan tahun dari SPP yang dipilih
            'jumlah_tagihan' => $request->jumlah_tagihan,
            'status' => $request->status,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan berhasil diperbarui');
    }

    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();

        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan berhasil dihapus');
    }
} 















