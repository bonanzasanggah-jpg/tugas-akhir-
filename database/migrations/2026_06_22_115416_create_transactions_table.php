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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            
            // Gunakan unsignedBigInteger agar migrasi tidak error (bypass foreign key constraint)
            $table->unsignedBigInteger('user_id');
            
            // Menggunakan decimal(15, 2) lebih aman untuk perhitungan uang (sampai 99 triliun)
            $table->decimal('total_price', 15, 2);
            $table->decimal('paid_amount', 15, 2);
            $table->decimal('change_amount', 15, 2);
            
            $table->enum('payment_method', ['cash'])->default('cash');
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->timestamps();

            // Jika nanti tabel 'users' sudah benar-benar siap, 
            // Anda bisa aktifkan baris relasi ini:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};