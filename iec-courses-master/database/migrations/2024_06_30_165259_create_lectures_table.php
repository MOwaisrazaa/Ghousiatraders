<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('lectures', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('course_id'); // Foreign key to courses table
        $table->string('name'); // Lecture name
        $table->text('description')->nullable(); // Description for the lecture
        $table->decimal('weekly_price', 8, 2)->nullable(); // Weekly price, optional
        $table->decimal('monthly_price', 8, 2)->nullable(); // Monthly price, optional
        $table->string('youtube_url')->nullable(); // YouTube URL for the lecture
        $table->string('image_path')->nullable(); // Path to the lecture image
        $table->timestamps();

        // Add foreign key constraint
        $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
