<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('area_parkir', function (Blueprint $table) {
            $table->id();
            $table->string('nama_area', 100);
            $table->decimal('persentase_penuh', 5, 2);
        });
    }

    public function down(): void {
        Schema::dropIfExists('area_parkir');
    }
};
