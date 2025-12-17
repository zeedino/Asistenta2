<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keputusan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sk')->unique();
            $table->date('tanggal_sk');
            $table->string('tahun_akademik'); // Format: 2024/2025
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->foreignId('mahasiswa_id')->constrained('users'); // ✅ Mahasiswa yang dibimbing
            $table->foreignId('admin_id')->constrained('users');
            $table->string('file_sk')->nullable();
            $table->enum('status', ['active', 'expired', 'revoked'])
                ->default('active');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keputusan');
    }
};
