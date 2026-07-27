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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('slug');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(10)->after('image_path');
            }
            if (!Schema::hasColumn('products', 'low_stock_threshold')) {
                $table->integer('low_stock_threshold')->default(5)->after('stock');
            }
            if (!Schema::hasColumn('products', 'status')) {
                $table->string('status', 20)->default('active')->after('low_stock_threshold'); // active, draft, inactive
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('weekly_price');
            }
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('sale_price');
            }
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('is_featured');
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sku',
                'stock',
                'low_stock_threshold',
                'status',
                'is_featured',
                'sale_price',
                'cost_price',
                'meta_title',
                'meta_description',
            ]);
        });
    }
};
