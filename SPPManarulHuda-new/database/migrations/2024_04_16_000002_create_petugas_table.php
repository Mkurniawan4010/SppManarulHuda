<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('petugas');
        
        Schema::create('petugas', function (Blueprint $table) {
            $table->id('id_petugas');
            $table->string('username', 25)->unique();
            $table->string('password');
            $table->string('nama_petugas', 35);
            $table->enum('level', ['bendahara', 'kepala_sekolah', 'ketua_yayasan']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('petugas');
    }
};

