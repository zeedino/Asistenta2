<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->text('activity_description');
            $table->text('progress');
            $table->text('obstacles')->nullable();
            $table->text('next_plan')->nullable();
            $table->enum('status', ['draft', 'submitted', 'validated', 'rejected'])->default('draft');
            $table->text('dosen_feedback')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index(['mahasiswa_id', 'status']);
            $table->index(['dosen_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
