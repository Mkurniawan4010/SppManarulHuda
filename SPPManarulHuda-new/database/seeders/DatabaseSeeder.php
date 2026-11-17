<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Petugas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user with a more unique username
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'test_user_' . time(), // Make username unique using timestamp
        ]);

        // Create bendahara user
        Petugas::create([
            'username' => 'bendahara',
            'password' => Hash::make('123'),
            'nama_petugas' => 'Bendahara',
            'level' => 'bendahara',
        ]);

        // Call PetugasSeeder for additional petugas
        $this->call([
            PetugasSeeder::class,
        ]);
    }
}




