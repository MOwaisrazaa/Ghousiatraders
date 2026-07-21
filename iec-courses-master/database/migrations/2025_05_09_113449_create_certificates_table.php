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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('certificate_request_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('file_path')->nullable(); // Path to the certificate PDF or image
            $table->timestamp('issue_date');
            $table->timestamp('expiry_date')->nullable();
            $table->timestamps();
            
            // Ensure a user can only have one certificate per course
            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
