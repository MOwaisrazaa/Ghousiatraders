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
        // Add lecture_id to certificate_requests table if it doesn't exist
        if (!Schema::hasColumn('certificate_requests', 'lecture_id')) {
            Schema::table('certificate_requests', function (Blueprint $table) {
                $table->foreignId('lecture_id')->nullable()->after('course_id')->constrained()->onDelete('cascade');
            });
        }
        
        // Drop old unique constraint and add new one for certificate_requests
        try {
            Schema::table('certificate_requests', function (Blueprint $table) {
                $table->dropUnique(['user_id', 'course_id']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }
        
        try {
            Schema::table('certificate_requests', function (Blueprint $table) {
                $table->unique(['user_id', 'course_id', 'lecture_id']);
            });
        } catch (\Exception $e) {
            // Constraint might already exist, continue
        }
        
        // Add lecture_id to certificates table if it doesn't exist
        if (!Schema::hasColumn('certificates', 'lecture_id')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->foreignId('lecture_id')->nullable()->after('course_id')->constrained()->onDelete('cascade');
            });
        }
        
        // Drop old unique constraint and add new one for certificates
        try {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropUnique(['user_id', 'course_id']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }
        
        try {
            Schema::table('certificates', function (Blueprint $table) {
                $table->unique(['user_id', 'course_id', 'lecture_id']);
            });
        } catch (\Exception $e) {
            // Constraint might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_requests', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'course_id', 'lecture_id']);
            $table->dropForeign(['lecture_id']);
            $table->dropColumn('lecture_id');
        });
        
        Schema::table('certificate_requests', function (Blueprint $table) {
            $table->unique(['user_id', 'course_id']);
        });
        
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'course_id', 'lecture_id']);
            $table->dropForeign(['lecture_id']);
            $table->dropColumn('lecture_id');
        });
        
        Schema::table('certificates', function (Blueprint $table) {
            $table->unique(['user_id', 'course_id']);
        });
    }
};
