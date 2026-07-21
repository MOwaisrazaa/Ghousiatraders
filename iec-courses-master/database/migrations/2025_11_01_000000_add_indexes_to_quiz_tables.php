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
        // Indexes already exist in database - this migration is a no-op
        // The indexes were created previously:
        // - idx_attempts_user_quiz_status on quiz_attempts
        // - idx_attempts_status on quiz_attempts
        // - idx_answers_attempt_question on quiz_answers
        // - idx_answers_review_status on quiz_answers
        // - idx_questions_quiz_id on quiz_questions
        // - idx_options_question_correct on quiz_options
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: indexes remain in database
    }
};
