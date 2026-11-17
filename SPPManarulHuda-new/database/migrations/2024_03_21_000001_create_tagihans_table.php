<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id', 10);
            $table->foreign('siswa_id')->references('nisn')->on('siswas')->onDelete('cascade');
            $table->unsignedBigInteger('spp_id');
            $table->foreign('spp_id')->references('id_spp')->on('spp')->onDelete('cascade');
            $table->string('bulan');
            $table->integer('tahun');
            $table->decimal('jumlah_tagihan', 10, 2);
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->date('tanggal_jatuh_tempo');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihans');
    }
}; 