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
        // First, check if unique constraint already exists
        $indexExists = \DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
            AND table_name = 'quiz_answers' 
            AND index_name = 'unique_attempt_question'
        ");
        
        if ($indexExists[0]->count > 0) {
            return; // Already exists, skip
        }
        
        // Remove any duplicate answers (keep the latest one)
        \DB::statement('
            DELETE qa1 FROM quiz_answers qa1
            INNER JOIN quiz_answers qa2 
            WHERE qa1.quiz_attempt_id = qa2.quiz_attempt_id 
            AND qa1.quiz_question_id = qa2.quiz_question_id
            AND qa1.id < qa2.id
        ');
        
        // Add unique constraint
        Schema::table('quiz_answers', function (Blueprint $table) {
            $table->unique(['quiz_attempt_id', 'quiz_question_id'], 'unique_attempt_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_answers', function (Blueprint $table) {
            $table->dropUnique('unique_attempt_question');
        });
    }
};
