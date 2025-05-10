<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("data_mahasiswa_lintas_fakultas", function (Blueprint $table) {
            $table->id();
            $table->text("foto_mahasiswa")->nullable();
            $table->string("nama", 100);
            $table->string("nim", 20)->unique();
            $table->string("fakultas_asal", 100);
            $table->string("tujuan_masuk", 150);
            $table->text("catatan")->nullable();
            $table->dateTime("waktu");
            $table->foreignId("petugas_id")->constrained("petugas")->onDelete("cascade");
        });
    }

    public function down(): void {
        Schema::dropIfExists("data_mahasiswa_lintas_fakultas");
    }
};
