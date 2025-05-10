<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataMahasiswaLintasFakultasSeeder extends Seeder {
    public function run(): void {
        DB::table('data_mahasiswa_lintas_fakultas')->insert([
            'foto_mahasiswa' => null,
            'nama' => 'Andi Mahasiswa',
            'nim' => '123456789',
            'fakultas_asal' => 'Teknik',
            'tujuan_masuk' => 'Fakultas Hukum',
            'catatan' => 'Pindah jalur minat',
            'waktu' => now(),
            'petugas_id' => 1,
        ]);
    }
}
