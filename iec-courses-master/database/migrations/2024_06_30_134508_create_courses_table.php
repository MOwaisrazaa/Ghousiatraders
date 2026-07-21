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
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Name of the course
        $table->text('description'); // HTML content from the editor
        $table->decimal('weekly_price', 8, 2); // Weekly price
        $table->decimal('monthly_price', 8, 2); // Monthly price
        $table->string('image_path'); // Path for the course image
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
