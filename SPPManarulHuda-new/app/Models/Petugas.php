<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petugas extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    protected $guarded = ['id_petugas'];

    protected $fillable = [
        'username',
        'password',
        'nama_petugas',
        'level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'id_petugas', 'id_petugas');
    }
    
    // Helper method untuk memeriksa level
    public function isBendahara()
    {
        return $this->level === 'bendahara';
    }
    
    public function isKepalaSekolah()
    {
        return $this->level === 'kepala_sekolah';
    }
    
    public function isKetuaYayasan()
    {
        return $this->level === 'ketua_yayasan';
    }
}

