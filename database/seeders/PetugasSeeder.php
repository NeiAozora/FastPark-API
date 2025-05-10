<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PetugasSeeder extends Seeder {
    public function run(): void {
        DB::table('petugas')->insert([
            'nama' => 'Admin Utama',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'email' => 'admin@example.com',
            'no_hp' => '08123456789',
            'status' => 'aktif',
            'role' => 'admin',
            'dibuat_pada' => now(),
            'terakhir_login' => now(),
        ]);
    }
}
