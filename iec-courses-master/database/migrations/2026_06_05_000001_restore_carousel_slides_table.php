<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('carousel_slides')) {
            return;
        }

        Schema::create('carousel_slides', function (Blueprint $table) {
            $table->id();

            $table->string('title', 100);
            $table->text('subtitle');
            $table->string('cta_text', 50);
            $table->string('cta_url', 255);
            $table->string('image_name', 100);

            $table->unsignedTinyInteger('order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->index(['is_active', 'order']);

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

    public function down(): void
    {
        Schema::dropIfExists('carousel_slides');
    }
};
