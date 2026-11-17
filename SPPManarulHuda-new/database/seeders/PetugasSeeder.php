<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run()
    {
        // Create kepala sekolah user
        DB::table('petugas')->insert([
            'username' => 'kepsek',
            'password' => Hash::make('kepsek123'),
            'nama_petugas' => 'Kepala Sekolah',
            'level' => 'kepala_sekolah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Create ketua yayasan user
        DB::table('petugas')->insert([
            'username' => 'ketua_yayasan',
            'password' => Hash::make('yayasan123'),
            'nama_petugas' => 'Ketua Yayasan',
            'level' => 'ketua_yayasan',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
} 



