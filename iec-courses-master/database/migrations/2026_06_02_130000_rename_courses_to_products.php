<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('shoppingcarts')) {
            try {
                Schema::table('shoppingcarts', function (Blueprint $table) {
                    $table->dropForeign(['course_id']);
                });
            } catch (\Throwable $e) {
            }
        }

        if (Schema::hasTable('courses') && !Schema::hasTable('products')) {
            Schema::rename('courses', 'products');
        }

        if (Schema::hasTable('shoppingcarts')) {
            Schema::table('shoppingcarts', function (Blueprint $table) {
                $table->foreign('course_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('shoppingcarts')) {
            try {
                Schema::table('shoppingcarts', function (Blueprint $table) {
                    $table->dropForeign(['course_id']);
                });
            } catch (\Throwable $e) {
            }
        }

        if (Schema::hasTable('products') && !Schema::hasTable('courses')) {
            Schema::rename('products', 'courses');
        }

        if (Schema::hasTable('shoppingcarts')) {
            Schema::table('shoppingcarts', function (Blueprint $table) {
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            });
        }
    }
};
