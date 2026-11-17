<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Spp;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('level:bendahara,ketua_yayasan');
    }
    
    public function index()
    {
        $tahun_spp = Spp::distinct()->pluck('tahun');
        return view('laporan.index', compact('tahun_spp'));
    }
    
    public function generatePDF(Request $request)
    {
        $request->validate([
            'bulan' => 'nullable|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000|max:2099', // Ubah tahun menjadi required
            'tipe_laporan' => 'required|in:semua,belum_bayar',
        ]);
        
        $query = Pembayaran::with(['petugas', 'siswa', 'spp']);
        
        // Filter berdasarkan bulan
        if ($request->bulan) {
            $query->whereMonth('tgl_bayar', $request->bulan);
        }
        
        // Filter berdasarkan tahun (selalu ada karena required)
        $query->whereYear('tgl_bayar', $request->tahun);
        
        // Ambil data pembayaran
        $pembayaran_data = $query->get();
        
        // Urutkan pembayaran berdasarkan kelas dan nama siswa
        $pembayaran = $pembayaran_data->sortBy(function($item) {
            // Jika siswa tidak ada, letakkan di akhir
            if (!$item->siswa || !$item->siswa->kelas) {
                return 'ZZZ-ZZZ';
            }
            // Urutkan berdasarkan kelas terlebih dahulu, kemudian nama
            return $item->siswa->kelas->nama_kelas . '-' . $item->siswa->nama;
        })->values(); // Reset indeks array agar berurutan
        
        $bulan_nama = $request->bulan ? Carbon::create(2000, $request->bulan, 1)->translatedFormat('F') : 'Semua Bulan';
        $tahun = $request->tahun;
        
        // Jika tipe laporan adalah siswa yang belum bayar
        if ($request->tipe_laporan === 'belum_bayar') {
            // Ambil semua siswa
            $siswa = \App\Models\Siswa::with('kelas')->get();
            
            // Filter siswa yang belum bayar pada bulan dan tahun yang dipilih
            $siswa_belum_bayar = $siswa->map(function($siswa) use ($request) {
                $bulan = $request->bulan ?: Carbon::now()->month;
                $tahun = $request->tahun;
                
                // Cek apakah siswa sudah bayar pada bulan dan tahun tersebut
                $sudah_bayar = Pembayaran::where('nisn', $siswa->nisn)
                    ->whereMonth('tgl_bayar', $bulan)
                    ->whereYear('tgl_bayar', $tahun)
                    ->exists();
                
                // Dapatkan pembayaran terakhir siswa
                $pembayaran_terakhir = Pembayaran::where('nisn', $siswa->nisn)
                    ->orderBy('tgl_bayar', 'desc')
                    ->first();
                
                $bulan_terakhir_bayar = null;
                if ($pembayaran_terakhir) {
                    $bulan_terakhir_bayar = Carbon::parse($pembayaran_terakhir->tgl_bayar)->format('F Y');
                }
                
                return [
                    'siswa' => $siswa,
                    'sudah_bayar' => $sudah_bayar,
                    'bulan_terakhir_bayar' => $bulan_terakhir_bayar
                ];
            })->filter(function($item) {
                // Kembalikan siswa yang belum bayar
                return !$item['sudah_bayar'];
            })->sortBy(function($item) {
                // Urutkan berdasarkan kelas terlebih dahulu, kemudian nama
                $kelas = $item['siswa']->kelas->nama_kelas ?? 'ZZZ'; // ZZZ untuk siswa tanpa kelas agar muncul di akhir
                return $kelas . '-' . $item['siswa']->nama;
            })->values(); // Reset indeks array agar berurutan
            
            // Load view untuk siswa yang belum bayar
            $pdf = PDF::loadView('laporan.pdf_belum_bayar', compact('siswa_belum_bayar', 'bulan_nama', 'tahun'));
            
            return $pdf->download('laporan-siswa-belum-bayar-spp-' . time() . '.pdf');
        }
        
        // Jika tipe laporan adalah semua pembayaran (default)
        $pdf = PDF::loadView('laporan.pdf', compact('pembayaran', 'bulan_nama', 'tahun'));
        
        return $pdf->download('laporan-pembayaran-spp-' . time() . '.pdf');
    }
}







