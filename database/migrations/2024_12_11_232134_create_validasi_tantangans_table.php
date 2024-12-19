<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('validasi_tantangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tantangan_harian_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('bukti')->nullable(); // Nama kolom sesuai dengan controller
            $table->boolean('is_validated')->default(false); // Nama kolom sesuai dengan controller
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validasi_tantangans'); // Nama tabel disesuaikan
    }
};
