<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::table('pembayarans', function (Blueprint $table) {
            // Hapus foreign key constraint yang lama
            $table->dropForeign(['nisn']);
            
            // Tambahkan foreign key constraint baru dengan CASCADE UPDATE dan CASCADE DELETE
            $table->foreign('nisn')
                  ->references('nisn')
                  ->on('siswas')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::table('pembayarans', function (Blueprint $table) {
            // Hapus foreign key constraint dengan cascade
            $table->dropForeign(['nisn']);
            
            // Kembalikan ke foreign key constraint yang lama tanpa cascade update
            $table->foreign('nisn')
                  ->references('nisn')
                  ->on('siswas')
                  ->onDelete('cascade');
        });
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};