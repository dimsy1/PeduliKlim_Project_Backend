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
        Schema::create('konten_edukasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi_konten');
            $table->enum('tipe_konten', ['artikel', 'video']);
            $table->boolean('is_published')->default(false);
            $table->string('thumbnail')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_edukasis');
    }
};
