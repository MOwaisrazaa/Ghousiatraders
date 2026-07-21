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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->integer('current_question_index')->default(0)->after('status')
                ->comment('Index of the current question being answered (0-based)');
            $table->timestamp('current_question_started_at')->nullable()->after('current_question_index')
                ->comment('When the current question timer started');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['current_question_index', 'current_question_started_at']);
        });
    }
};

