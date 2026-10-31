<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus atau beri tanda // pada baris factory jika ada:
        // \App\Models\User::factory(10)->create(); 

        // Panggil UserSeeder buatan Anda sendiri:
        $this->call([
            UserSeeder::class,
        ]);
    }
}