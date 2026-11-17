<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';
    protected $fillable = [
        'siswa_id',
        'spp_id',
        'bulan',
        'tahun',
        'jumlah_tagihan',
        'status',
        'tanggal_jatuh_tempo',
        'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function spp()
    {
        return $this->belongsTo(SPP::class, 'spp_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'tagihan_id');
    }

    // Method untuk mengecek total pembayaran
    public function getTotalPembayaranAttribute()
    {
        return $this->pembayaran()->sum('jumlah_bayar');
    }

    // Method untuk mengecek apakah sudah lunas
    public function getIsLunasAttribute()
    {
        return $this->total_pembayaran >= $this->jumlah_tagihan;
    }

    // Method untuk update status berdasarkan pembayaran
    public function updateStatus()
    {
        $this->status = $this->is_lunas ? 'lunas' : 'belum_lunas';
        $this->save();
    }
} 