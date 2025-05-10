<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaParkirSeeder extends Seeder {
    public function run(): void {
        DB::table('area_parkir')->insert([
            ['nama_area' => 'Parkir A', 'persentase_penuh' => 75.50],
            ['nama_area' => 'Parkir B', 'persentase_penuh' => 60.25],
        ]);
    }
}
