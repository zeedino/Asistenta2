<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('availability_id')->constrained('availabilities')->onDelete('cascade');
            $table->string('title');
            $table->text('agenda');
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('dosen_notes')->nullable();
            $table->text('mahasiswa_notes')->nullable();
            $table->timestamp('meeting_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
