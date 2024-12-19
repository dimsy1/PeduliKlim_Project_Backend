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
        Schema::create('tantangan_harians', function (Blueprint $table) {
            $table->id();
            $table->string('aktivitas');
            $table->text('deskripsi_aktivitas');
            $table->unsignedInteger('poin')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tantangan_harians');
    }
};
