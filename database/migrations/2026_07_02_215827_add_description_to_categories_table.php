<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Menambahkan kolom deskripsi setelah nama kategori, boleh kosong (nullable)
            $table->text('description')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};