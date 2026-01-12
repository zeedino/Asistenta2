<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_mahasiswa', function (Blueprint $table) {
            $table->id();

            // Jika User Dosen dihapus, data pembimbing ikut terhapus
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');

            // Jika User Mahasiswa dihapus, data bimbingan ikut terhapus
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');

            // Relasi ke SK
            $table->foreignId('sk_id')->constrained('surat_keputusan')->onDelete('cascade');

            $table->enum('posisi', ['Pembimbing 1', 'Pembimbing 2']);
            $table->timestamps();

            // Unique constraint:
            // 1. Satu mahasiswa tidak boleh punya 2 pembimbing 1 di SK yang sama
            $table->unique(['mahasiswa_id', 'sk_id', 'posisi']);

            // 2. Satu dosen tidak boleh jadi pembimbing 1 dan 2 untuk mahasiswa yang sama di SK yang sama
            $table->unique(['dosen_id', 'mahasiswa_id', 'sk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_mahasiswa');
    }
};
