<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('meeting_id')->nullable()->constrained('meetings')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size');
            $table->string('file_type');

            // ✅ CATEGORY DENGAN BAHASA INDONESIA KONSISTEN
            $table->enum('category', [
                'proposal',
                'revisi',
                'laporan',
                'presentasi',
                'draft',
                'final',
                'lainnya',
            ])->default('lainnya');

            $table->integer('version')->default(1);

            $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'rejected'])->default('draft');

            $table->text('dosen_feedback')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['meeting_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
