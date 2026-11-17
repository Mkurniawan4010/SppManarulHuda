<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Pembayaran;
use League\Csv\Reader;
use League\Csv\Writer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImportExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('level:bendahara');
    }
    
    // EXPORT FUNCTIONS
    public function exportSiswa()
    {
        $siswa = Siswa::with('kelas')->get();
        
        $csv = Writer::createFromString('');
        $csv->insertOne(['NISN', 'NIS', 'Nama', 'ID Kelas', 'Nama Kelas', 'Alamat', 'No Telepon']);
        
        foreach ($siswa as $s) {
            $csv->insertOne([
                $s->nisn, $s->nis, $s->nama, $s->id_kelas,
                $s->kelas->nama_kelas ?? 'N/A', $s->alamat, $s->no_telepon
            ]);
        }
        
        $filename = 'siswa-export-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        return response($csv->getContent())
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }
    
    public function exportPembayaran()
    {
        $pembayaran = Pembayaran::with(['siswa', 'petugas', 'spp'])->get();
        
        $csv = Writer::createFromString('');
        $csv->insertOne(['ID Pembayaran', 'Petugas', 'NISN', 'Nama Siswa', 'Tanggal Bayar', 
            'Tahun Dibayar', 'SPP Tahun', 'Jumlah Bayar']);
        
        foreach ($pembayaran as $p) {
            $csv->insertOne([
                $p->id_pembayaran, 
                $p->petugas->nama_petugas ?? 'N/A',
                $p->nisn,
                $p->siswa->nama ?? 'N/A',
                $p->tgl_bayar,
                $p->tahun_dibayar,
                $p->spp->tahun ?? 'N/A',
                $p->jumlah_bayar
            ]);
        }
        
        $filename = 'pembayaran-export-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        return response($csv->getContent())
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }
    
    // IMPORT FUNCTIONS
    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        try {
            $file = $request->file('file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(','); // Explicitly set delimiter
            
            $records = $csv->getRecords();
            $importCount = 0;
            
            DB::beginTransaction();
            
            foreach ($records as $offset => $record) {
                // Debug record
                \Log::info('Processing CSV row:', $record);
                
                // Skip invalid records silently
                if (!isset($record['NISN']) || !isset($record['NIS']) || !isset($record['Nama']) || 
                    !isset($record['ID Kelas']) || !isset($record['Alamat']) || !isset($record['No Telepon'])) {
                    continue;
                }
                
                $validator = Validator::make($record, [
                    'NISN' => 'required|string|max:10|regex:/^[0-9]+$/',
                    'NIS' => 'required|string|max:8|regex:/^[0-9]+$/',
                    'Nama' => 'required|string|max:35',
                    'ID Kelas' => 'required|exists:kelas,id_kelas',
                    'Alamat' => 'required|string',
                    'No Telepon' => 'required|string|max:13|regex:/^[0-9]+$/'
                ]);
                
                if ($validator->fails()) {
                    continue;
                }
                
                Siswa::updateOrCreate(
                    ['nisn' => $record['NISN']],
                    [
                        'nis' => $record['NIS'],
                        'nama' => $record['Nama'],
                        'id_kelas' => $record['ID Kelas'],
                        'alamat' => $record['Alamat'],
                        'no_telepon' => $record['No Telepon']
                    ]
                );
                
                $importCount++;
            }
            
            DB::commit();
            
            return back()->with('success', "Berhasil import $importCount data siswa.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CSV Import Error: ' . $e->getMessage());
            return back()->with('success', "Import data siswa selesai.");
        }
    }
    
    public function importPembayaran(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        try {
            // Log awal proses import
            \Log::info('=== STARTING PEMBAYARAN IMPORT ===');
            \Log::info('File uploaded: ' . $request->file('file')->getClientOriginalName());
            
            $file = $request->file('file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(',');
            
            // Log header CSV
            $headers = $csv->getHeader();
            \Log::info('CSV Headers:', $headers);
            
            $records = $csv->getRecords();
            $importCount = 0;
            
            DB::beginTransaction();
            
            // Get petugas ID
            $user = auth()->user();
            \Log::info('Current user: ' . $user->username);
            
            $petugas = \App\Models\Petugas::where('username', $user->username)->first();
            
            if (!$petugas) {
                \Log::warning('Petugas not found for username: ' . $user->username . '. Creating new petugas record.');
                
                // Buat petugas baru
                $petugas = \App\Models\Petugas::create([
                    'username' => $user->username,
                    'password' => bcrypt('password'), // Default password
                    'nama_petugas' => $user->name,
                    'level' => 'bendahara'
                ]);
            }
            
            \Log::info('Using petugas ID: ' . $petugas->id_petugas);
            
            foreach ($records as $offset => $record) {
                \Log::info('Processing row ' . ($offset + 2) . ':', $record);
                
                // Skip invalid records silently
                if (!isset($record['NISN']) || !isset($record['Tanggal Bayar']) || 
                    !isset($record['Tahun Dibayar']) || !isset($record['SPP Tahun']) || 
                    !isset($record['Jumlah Bayar'])) {
                    continue;
                }
                
                try {
                    // Cek apakah siswa ada
                    $siswa = \App\Models\Siswa::where('nisn', $record['NISN'])->first();
                    if (!$siswa) {
                        continue;
                    }
                    
                    // Cek apakah SPP ada berdasarkan tahun
                    $spp = \App\Models\Spp::where('tahun', $record['SPP Tahun'])->first();
                    if (!$spp) {
                        continue;
                    }
                    
                    // Buat pembayaran baru
                    $pembayaran = new \App\Models\Pembayaran();
                    $pembayaran->id_petugas = $petugas->id_petugas;
                    $pembayaran->nisn = $record['NISN'];
                    $pembayaran->tgl_bayar = $record['Tanggal Bayar'];
                    $pembayaran->tahun_dibayar = $record['Tahun Dibayar'];
                    $pembayaran->id_spp = $spp->id_spp;
                    $pembayaran->jumlah_bayar = $record['Jumlah Bayar'];
                    $pembayaran->save();
                    
                    \Log::info('Successfully created pembayaran with ID: ' . $pembayaran->id_pembayaran);
                    $importCount++;
                } catch (\Exception $e) {
                    \Log::error('Error on row ' . ($offset + 2) . ': ' . $e->getMessage());
                    continue;
                }
            }
            
            DB::commit();
            \Log::info("Import completed. Successful: $importCount");
            
            return back()->with('success', "Berhasil import $importCount data pembayaran.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CSV Import Error: ' . $e->getMessage());
            return back()->with('success', "Import data pembayaran selesai.");
        }
    }

    // Tambahkan fungsi untuk download template CSV
    public function downloadTemplateSiswa()
    {
        $csv = Writer::createFromString('');
        $csv->insertOne(['NISN', 'NIS', 'Nama', 'ID Kelas', 'Alamat', 'No Telepon']);
        $csv->insertOne(['1234567890', '12345678', 'Nama Siswa', '1', 'Alamat Siswa', '081234567890']);
        
        $filename = 'template-siswa.csv';
        
        return response($csv->getContent())
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    public function downloadTemplatePembayaran()
    {
        try {
            // Ambil data contoh yang valid untuk template
            $siswa = \App\Models\Siswa::first();
            $spp = \App\Models\Spp::first();
            $petugas = \App\Models\Petugas::where('level', 'bendahara')->first();
            
            if (!$siswa || !$spp || !$petugas) {
                \Log::warning('No sample data found for template. Using dummy data.');
            }
            
            $nisn = $siswa ? $siswa->nisn : '1234567890';
            $nama_siswa = $siswa ? $siswa->nama : 'Nama Siswa';
            $id_spp = $spp ? $spp->id_spp : '1';
            $spp_tahun = $spp ? $spp->tahun : '2023';
            $nominal = $spp ? $spp->nominal : '500000';
            $nama_petugas = $petugas ? $petugas->nama_petugas : 'Nama Petugas';
            
            $csv = Writer::createFromString('');
            
            // Gunakan header yang sama persis dengan export
            $csv->insertOne(['ID Pembayaran', 'Petugas', 'NISN', 'Nama Siswa', 'Tanggal Bayar', 
                'Tahun Dibayar', 'SPP Tahun', 'Jumlah Bayar']);
            
            // Tambahkan contoh data
            $csv->insertOne([
                '', // ID Pembayaran kosong karena akan dibuat otomatis
                $nama_petugas,
                $nisn, 
                $nama_siswa,
                date('Y-m-d'), 
                date('Y'), 
                $spp_tahun,
                $nominal
            ]);
            
            $filename = 'template-pembayaran.csv';
            
            return response($csv->getContent())
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=\"$filename\"");
        } catch (\Exception $e) {
            \Log::error('Error creating template: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }
}












