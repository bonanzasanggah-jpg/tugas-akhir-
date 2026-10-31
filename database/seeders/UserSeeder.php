<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Bonanza',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karyawan Toko',
                'email' => 'karyawan@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}