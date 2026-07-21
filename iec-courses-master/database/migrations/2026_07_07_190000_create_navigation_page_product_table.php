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
        if (!Schema::hasTable('navigation_page_product')) {
            Schema::create('navigation_page_product', function (Blueprint $table) {
                $table->id();
                $table->foreignId('navigation_page_id')->constrained('navigation_pages')->onDelete('cascade');
                // The courses table was renamed to products
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
        Schema::dropIfExists('navigation_page_product');
    }
};
