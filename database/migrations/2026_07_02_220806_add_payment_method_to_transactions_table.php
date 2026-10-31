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
        Schema::table('transactions', function (Blueprint $table) {
            // Cek apakah kolom sudah ada agar tidak terjadi error "Duplicate column name"
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->enum('payment_method', ['tunai', 'qris'])
                      ->default('tunai')
                      ->after('total_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Cek apakah kolom ada sebelum mencoba menghapusnya
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};