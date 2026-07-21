<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing unique constraints on certificate_requests
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'certificate_requests' 
            AND CONSTRAINT_TYPE = 'UNIQUE'
        ");
        
        // Drop all unique constraints
        foreach ($constraints as $constraint) {
            try {
                DB::statement("ALTER TABLE certificate_requests DROP INDEX {$constraint->CONSTRAINT_NAME}");
            } catch (\Exception $e) {
                // Continue if constraint doesn't exist
            }
        }
        
        // Add the correct unique constraint that includes lecture_id
        Schema::table('certificate_requests', function (Blueprint $table) {
            $table->unique(['user_id', 'course_id', 'lecture_id'], 'certificate_requests_user_course_lecture_unique');
        });
        
        // Do the same for certificates table
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'certificates' 
            AND CONSTRAINT_TYPE = 'UNIQUE'
        ");
        
        foreach ($constraints as $constraint) {
            try {
                DB::statement("ALTER TABLE certificates DROP INDEX {$constraint->CONSTRAINT_NAME}");
            } catch (\Exception $e) {
                // Continue if constraint doesn't exist
            }
        }
        
        Schema::table('certificates', function (Blueprint $table) {
            $table->unique(['user_id', 'course_id', 'lecture_id'], 'certificates_user_course_lecture_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_requests', function (Blueprint $table) {
            $table->dropUnique('certificate_requests_user_course_lecture_unique');
            $table->unique(['user_id', 'course_id']);
        });
        
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique('certificates_user_course_lecture_unique');
            $table->unique(['user_id', 'course_id']);
        });
    }
};
