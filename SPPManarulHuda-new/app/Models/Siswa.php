<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';
    protected $primaryKey = 'nisn';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nisn',
        'nis',
        'nama',
        'id_kelas',
        'alamat',
        'no_telepon'
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'nisn', 'nisn');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'siswa_id', 'nisn');
    }
} 