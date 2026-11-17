<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists before trying to modify it
        if (Schema::hasTable('pembayarans')) {
            Schema::table('pembayarans', function (Blueprint $table) {
                // Check if the foreign key exists before dropping it
                if (Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('pembayarans')) {
                    // Hapus foreign key constraint yang lama
                    $table->dropForeign(['nisn']);
                }
                
                // Tambahkan foreign key constraint baru dengan cascade delete
                $table->foreign('nisn')
                      ->references('nisn')
                      ->on('siswas')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pembayarans')) {
            Schema::table('pembayarans', function (Blueprint $table) {
                // Hapus foreign key constraint dengan cascade
                $table->dropForeign(['nisn']);
                
                // Kembalikan ke foreign key constraint yang lama
                $table->foreign('nisn')
                      ->references('nisn')
                      ->on('siswas');
            });
        }
    }
};
