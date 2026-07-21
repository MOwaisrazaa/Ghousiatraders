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
        Schema::create('user_lecture_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('lecture_id')->constrained()->onDelete('cascade');
            $table->integer('current_time')->default(0); // Current position in seconds
            $table->integer('duration')->default(0); // Total duration in seconds
            $table->decimal('progress_percent', 5, 2)->default(0); // Progress percentage (0-100)
            $table->boolean('completed')->default(false); // Whether the lecture is considered completed
            $table->timestamp('last_watched_at')->nullable(); // When the user last watched this lecture
            $table->timestamps();

            // Unique constraint to ensure one record per user and lecture
            $table->unique(['user_id', 'lecture_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_lecture_progress');
    }
};
