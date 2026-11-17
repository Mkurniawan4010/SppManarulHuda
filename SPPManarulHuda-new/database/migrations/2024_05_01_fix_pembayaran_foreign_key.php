<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Nonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::table('pembayarans', function (Blueprint $table) {
            // Hapus foreign key constraint yang lama jika ada
            if (Schema::hasColumn('pembayarans', 'nisn')) {
                // Dapatkan nama constraint
                $foreignKeys = DB::select(
                    "SELECT CONSTRAINT_NAME
                     FROM information_schema.TABLE_CONSTRAINTS
                     WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'
                     AND TABLE_NAME = 'pembayarans'
                     AND CONSTRAINT_SCHEMA = '" . env('DB_DATABASE') . "'
                     AND CONSTRAINT_NAME LIKE '%nisn%'"
                );
                
                // Hapus semua foreign key yang terkait dengan kolom nisn
                foreach ($foreignKeys as $foreignKey) {
                    $table->dropForeign($foreignKey->CONSTRAINT_NAME);
                }
                
                // Tambahkan foreign key constraint baru dengan cascade delete
                $table->foreign('nisn')
                      ->references('nisn')
                      ->on('siswas')
                      ->onDelete('cascade');
            }
        });
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Hapus foreign key constraint dengan cascade jika ada
            if (Schema::hasColumn('pembayarans', 'nisn')) {
                $table->dropForeign(['nisn']);
                
                // Kembalikan ke foreign key constraint yang lama tanpa cascade
                $table->foreign('nisn')
                      ->references('nisn')
                      ->on('siswas');
            }
        });
    }
};