<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'answer_attachments',
            'answers',
            'account_balances',
            'account_transactions',
            'account_transfers',
            'account_usages',
            'admin_permissions',
            'admin_user_assignments',
            'accounts',
            'certificate_requests',
            'certificates',
            'carousel_slides',
            'course_features',
            'course_materials',
            'instructor_profiles',
            'journal_entries',
            'lecture_features',
            'lectures',
            'question_attachments',
            'questions',
            'quiz_answers',
            'quiz_attempts',
            'quiz_options',
            'quiz_questions',
            'quizzes',
            'search_logs',
            'suggestions',
            'traffic_logs',
            'user_courses',
            'user_lecture_progress',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Intentionally left blank. These tables are not part of the Polani storefront schema.
    }
};
