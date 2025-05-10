<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("petugas", function (Blueprint $table) {
            $table->id();
            $table->string("nama", 100);
            $table->string("username", 50)->unique();
            $table->string("password", 255);
            $table->string("email", 100)->unique();
            $table->string("no_hp", 20)->nullable();
            $table->text("foto_profil")->nullable();
            $table->enum("status", ["aktif", "nonaktif"]);
            $table->enum("role", ["admin", "petugas"]);
            $table->dateTime("dibuat_pada")->nullable();
            $table->dateTime("terakhir_login")->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists("petugas");
    }
};
