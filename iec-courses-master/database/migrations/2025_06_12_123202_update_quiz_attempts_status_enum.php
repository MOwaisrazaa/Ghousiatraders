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
        // Update the status column to include 'pending_review'
        DB::statement("ALTER TABLE quiz_attempts MODIFY COLUMN status ENUM('in_progress', 'completed', 'passed', 'failed', 'pending_review') DEFAULT 'in_progress'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the status column to original enum values
        DB::statement("ALTER TABLE quiz_attempts MODIFY COLUMN status ENUM('in_progress', 'completed', 'passed', 'failed') DEFAULT 'in_progress'");
    }
};
