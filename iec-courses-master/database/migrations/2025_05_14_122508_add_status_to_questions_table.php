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
        Schema::table('questions', function (Blueprint $table) {
            // Add the status column with enum values and default to 'pending'
            $table->enum('status', ['pending', 'answered', 'rejected'])->default('pending');
            
            // Add the missing columns from the Question model that aren't in the migration
            if (!Schema::hasColumn('questions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            }
            
            if (!Schema::hasColumn('questions', 'course_id')) {
                $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('questions', 'title')) {
                $table->string('title')->nullable();
            }
            
            if (!Schema::hasColumn('questions', 'content_type')) {
                $table->string('content_type')->nullable();
            }
            
            if (!Schema::hasColumn('questions', 'content_id')) {
                $table->unsignedBigInteger('content_id')->nullable();
            }
            
            if (!Schema::hasColumn('questions', 'is_resolved')) {
                $table->boolean('is_resolved')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn([
                'user_id', 'course_id', 'title', 'content_type', 'content_id', 'is_resolved'
            ]);
        });
    }
};
