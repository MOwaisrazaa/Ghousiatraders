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
        if (!Schema::hasTable('homepage_sections')) {
            Schema::create('homepage_sections', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('homepage_section_product')) {
            Schema::create('homepage_section_product', function (Blueprint $table) {
                $table->id();
                $table->foreignId('homepage_section_id')->constrained('homepage_sections')->onDelete('cascade');
                $table->foreignId('course_id')->constrained('products')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_section_product');
        Schema::dropIfExists('homepage_sections');
    }
};
