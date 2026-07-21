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
        Schema::create('user_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('lecture_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->default('active'); // active, expired, etc.
            $table->timestamp('expires_at')->nullable(); // for subscription-based access
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            // The user can have multiple entries, but only one for each course or lecture
            $table->unique(['user_id', 'course_id', 'lecture_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_courses');
    }
};
