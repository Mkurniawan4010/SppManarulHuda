<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Hapus foreign key yang terhubung ke petugas
            $table->dropForeign(['id_petugas']);
        });
        
        // Tambahkan tabel lain yang mungkin memiliki foreign key ke petugas
        // Contoh:
        // Schema::table('other_table', function (Blueprint $table) {
        //     $table->dropForeign(['petugas_id']);
        // });
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');
        });
    }
};