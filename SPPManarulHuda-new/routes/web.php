<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\TagihanController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Ganti Auth::routes() dengan definisi rute manual
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard route tanpa controller
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');
    
    // Routes for bendahara only
    Route::middleware(['level:bendahara'])->group(function () {
        Route::resource('petugas', PetugasController::class);
        Route::resource('spp', SppController::class);
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        
        // Pembayaran hanya untuk bendahara
        Route::resource('pembayaran', PembayaranController::class);
        Route::get('/pembayaran/history/{nisn}', [PembayaranController::class, 'history'])->name('pembayaran.history');
    });

    // Routes for bendahara and ketua yayasan
    Route::middleware(['level:bendahara,ketua_yayasan'])->group(function () {
        Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/generate-pdf', [App\Http\Controllers\LaporanController::class, 'generatePDF'])->name('laporan.pdf');
    });

    // Routes for all authenticated users (bendahara, kepala_sekolah, ketua_yayasan)
    Route::middleware(['level:bendahara,kepala_sekolah,ketua_yayasan'])->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        Route::resource('kelas', KelasController::class);
        Route::resource('tagihan', TagihanController::class);
    });
});

// Import Export Routes
Route::middleware(['auth', 'level:bendahara'])->group(function () {
    // Export Routes
    Route::get('/export/siswa', [App\Http\Controllers\ImportExportController::class, 'exportSiswa'])->name('export.siswa');
    Route::get('/export/pembayaran', [App\Http\Controllers\ImportExportController::class, 'exportPembayaran'])->name('export.pembayaran');
    
    // Import Routes
    Route::post('/import/siswa', [App\Http\Controllers\ImportExportController::class, 'importSiswa'])->name('import.siswa');
    Route::post('/import/pembayaran', [App\Http\Controllers\ImportExportController::class, 'importPembayaran'])->name('import.pembayaran');

    // Template Routes
    Route::get('/template/siswa', [App\Http\Controllers\ImportExportController::class, 'downloadTemplateSiswa'])->name('template.siswa');
    Route::get('/template/pembayaran', [App\Http\Controllers\ImportExportController::class, 'downloadTemplatePembayaran'])->name('template.pembayaran');
});

// Pastikan route resource siswa sudah terdaftar
Route::resource('siswa', App\Http\Controllers\SiswaController::class);




