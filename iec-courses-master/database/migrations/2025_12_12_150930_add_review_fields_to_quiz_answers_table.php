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
        Schema::table('quiz_answers', function (Blueprint $table) {
            // Modify is_correct to allow null values
            $table->boolean('is_correct')->nullable()->change();

            // Add new review fields only if they don't exist
            if (!Schema::hasColumn('quiz_answers', 'review_status')) {
                $table->enum('review_status', ['auto_graded', 'pending_review', 'reviewed'])->default('auto_graded')->after('points_earned');
            }
            if (!Schema::hasColumn('quiz_answers', 'feedback')) {
                $table->text('feedback')->nullable()->after('review_status');
            }
            if (!Schema::hasColumn('quiz_answers', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('feedback');
            }
            if (!Schema::hasColumn('quiz_answers', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_answers', function (Blueprint $table) {
            // Remove the new columns
            $table->dropColumn(['review_status', 'feedback', 'reviewed_by', 'reviewed_at']);

            // Revert is_correct to not nullable
            $table->boolean('is_correct')->default(false)->change();
        });
    }
};
