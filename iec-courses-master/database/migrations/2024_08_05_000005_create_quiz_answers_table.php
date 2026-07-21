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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_question_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_option_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('answer_text')->nullable(); // For open-ended questions
            $table->boolean('is_correct')->nullable(); // null for pending review
            $table->integer('points_earned')->default(0);
            $table->enum('review_status', ['auto_graded', 'pending_review', 'reviewed'])->default('auto_graded');
            $table->text('feedback')->nullable(); // Admin feedback for text answers
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who reviewed
            $table->timestamp('reviewed_at')->nullable(); // When it was reviewed
            $table->timestamps();

            // Ensure a user can only answer a question once per attempt
            $table->unique(['quiz_attempt_id', 'quiz_question_id'], 'unique_question_attempt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
