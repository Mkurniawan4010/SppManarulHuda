<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pembayarans')) {
            Schema::rename('pembayaran', 'pembayarans');
        }

        Schema::table('pembayarans', function (Blueprint $table) {
            // Add tagihan_id column
            $table->unsignedBigInteger('tagihan_id')->nullable();
            $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['tagihan_id']);
            $table->dropColumn('tagihan_id');
        });

        if (Schema::hasTable('pembayarans')) {
            Schema::rename('pembayarans', 'pembayaran');
        }
    }
}; 