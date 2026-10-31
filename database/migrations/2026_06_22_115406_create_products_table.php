<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('buy_price', 10, 2);
            $table->decimal('sell_price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            $table->date('expired_date')->nullable();
            $table->enum('expired_status', ['aman', 'mendekati', 'kadaluarsa'])->default('aman');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};