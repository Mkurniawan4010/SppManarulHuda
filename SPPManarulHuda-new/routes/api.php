<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/spp/{id}', function($id) {
    $spp = \App\Models\SPP::findOrFail($id);
    return response()->json([
        'nominal' => $spp->nominal
    ]);
});

Route::get('/siswa/{nisn}/check-pembayaran', function($nisn) {
    $count = \App\Models\Pembayaran::where('nisn', $nisn)->count();
    return response()->json([
        'count' => $count
    ]);
});



