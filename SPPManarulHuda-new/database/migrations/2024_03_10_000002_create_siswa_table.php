<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->string('nisn', 10)->primary();
            $table->string('nis', 12);
            $table->string('nama', 35);
            $table->foreignId('id_kelas')->references('id_kelas')->on('kelas');
            $table->text('alamat');
            $table->string('no_telepon', 13);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswas');
    }
}; 