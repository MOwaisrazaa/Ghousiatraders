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
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'image_path')) {
                $table->string('image_path')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('categories', 'display_order')) {
                $table->integer('display_order')->default(0)->after('parent_id');
            }
            if (!Schema::hasColumn('categories', 'status')) {
                $table->string('status', 20)->default('active')->after('display_order'); // active, inactive, hidden
            }
            if (!Schema::hasColumn('categories', 'seo_title')) {
                $table->string('seo_title')->nullable()->after('status');
            }
            if (!Schema::hasColumn('categories', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('seo_title');
            }

            // Foreign key constraints
            try {
                $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            try {
                $table->dropForeign(['parent_id']);
            } catch (\Exception $e) {}

            $table->dropColumn([
                'description',
                'image_path',
                'parent_id',
                'display_order',
                'status',
                'seo_title',
                'meta_description',
            ]);
        });
    }
};
