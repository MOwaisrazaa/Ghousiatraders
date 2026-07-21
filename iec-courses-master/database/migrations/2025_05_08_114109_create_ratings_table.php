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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('rateable'); // Polymorphic relationship for courses and lectures
            $table->integer('rating')->comment('Rating value from 1-5');
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->boolean('show_publicly')->default(true);
            $table->timestamps();
            
            // Ensure a user can only rate once per item
            $table->unique(['user_id', 'rateable_type', 'rateable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
