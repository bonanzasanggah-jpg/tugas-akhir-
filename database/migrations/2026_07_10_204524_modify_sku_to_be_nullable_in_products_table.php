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
        Schema::table('products', function (Blueprint $table) {
            // Mengubah kolom sku menjadi nullable agar bisa dikosongkan
            $table->string('sku')->nullable()->change();
            
            // Opsional: Jika barcode juga ingin dibuat nullable (jaga-jaga agar aman)
            $table->string('barcode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Mengembalikan ke kondisi semula (NOT NULL) jika migrasi dibatalkan
            $table->string('sku')->nullable(false)->change();
            $table->string('barcode')->nullable(false)->change();
        });
    }
};