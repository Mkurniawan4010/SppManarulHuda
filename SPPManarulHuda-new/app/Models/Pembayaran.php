<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    protected $primaryKey = 'id_pembayaran';
    protected $fillable = [
        'id_petugas',
        'nisn',
        'id_spp',
        'tagihan_id',
        'tgl_bayar',
        'tahun_dibayar',
        'jumlah_bayar'
    ];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function spp()
    {
        return $this->belongsTo(SPP::class, 'id_spp', 'id_spp');
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
} 


