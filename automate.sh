#!/bin/bash

# Exit on error
set -e

echo "📦 Membuat model, migration, dan seeder untuk: petugas"
php artisan make:model Petugas -m -s

echo "📦 Membuat model, migration, dan seeder untuk: area_parkir"
php artisan make:model AreaParkir -m -s

echo "📦 Membuat model, migration, dan seeder untuk: data_mahasiswa_lintas_fakultas"
php artisan make:model DataMahasiswaLintasFakultas -m -s

echo "✏️ Menulis ulang file migrasi..."

# Overwrite migration isi
find database/migrations -name "*create_petugas_table.php" -exec bash -c 'cat > "$0" <<EOF
<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("petugas", function (Blueprint \$table) {
            \$table->id();
            \$table->string("nama", 100);
            \$table->string("username", 50)->unique();
            \$table->string("password", 255);
            \$table->string("email", 100)->unique();
            \$table->string("no_hp", 20)->nullable();
            \$table->text("foto_profil")->nullable();
            \$table->enum("status", ["aktif", "nonaktif"]);
            \$table->enum("role", ["admin", "petugas"]);
            \$table->dateTime("dibuat_pada")->nullable();
            \$table->dateTime("terakhir_login")->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists("petugas");
    }
};
EOF' {} \;

find database/migrations -name "*create_area_parkir_table.php" -exec bash -c 'cat > "$0" <<EOF
<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("area_parkir", function (Blueprint \$table) {
            \$table->id();
            \$table->string("nama_area", 100);
            \$table->decimal("persentase_penuh", 5, 2);
        });
    }

    public function down(): void {
        Schema::dropIfExists("area_parkir");
    }
};
EOF' {} \;

find database/migrations -name "*create_data_mahasiswa_lintas_fakultas_table.php" -exec bash -c 'cat > "$0" <<EOF
<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("data_mahasiswa_lintas_fakultas", function (Blueprint \$table) {
            \$table->id();
            \$table->text("foto_mahasiswa")->nullable();
            \$table->string("nama", 100);
            \$table->string("nim", 20)->unique();
            \$table->string("fakultas_asal", 100);
            \$table->string("tujuan_masuk", 150);
            \$table->text("catatan")->nullable();
            \$table->dateTime("waktu");
            \$table->foreignId("petugas_id")->constrained("petugas")->onDelete("cascade");
        });
    }

    public function down(): void {
        Schema::dropIfExists("data_mahasiswa_lintas_fakultas");
    }
};
EOF' {} \;

echo "📤 Mengisi seeder..."

# PetugasSeeder
cat > database/seeders/PetugasSeeder.php <<EOF
<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\Hash;
use Illuminate\\Support\\Facades\\DB;

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
EOF

# AreaParkirSeeder
cat > database/seeders/AreaParkirSeeder.php <<EOF
<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;

class AreaParkirSeeder extends Seeder {
    public function run(): void {
        DB::table('area_parkir')->insert([
            ['nama_area' => 'Parkir A', 'persentase_penuh' => 75.50],
            ['nama_area' => 'Parkir B', 'persentase_penuh' => 60.25],
        ]);
    }
}
EOF

# DataMahasiswaSeeder
cat > database/seeders/DataMahasiswaLintasFakultasSeeder.php <<EOF
<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;

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
EOF

# Tambahkan ke DatabaseSeeder
sed -i "/public function run()/a \\\t\t\$this->call([\n\t\t\tPetugasSeeder::class,\n\t\t\tAreaParkirSeeder::class,\n\t\t\tDataMahasiswaLintasFakultasSeeder::class,\n\t\t]);" database/seeders/DatabaseSeeder.php

echo "🧱 Migrasi database..."
php artisan migrate:fresh --seed

echo "✅ Selesai! Semua tabel, model, dan data awal berhasil dibuat."
