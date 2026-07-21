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
        Schema::create('carousel_slides', function (Blueprint $table) {
            $table->id();

            // Content fields
            $table->string('title', 100);
            $table->text('subtitle');
            $table->string('cta_text', 50);
            $table->string('cta_url', 255);

            // Image storage (filename only, not full path)
            $table->string('image_name', 100);

            // Ordering and status
            $table->unsignedTinyInteger('order')->default(0);
            $table->boolean('is_active')->default(true);

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index(['is_active', 'order']);

            // Foreign keys
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousel_slides');
    }
};
