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
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'min_order_amount')) {
                $table->decimal('min_order_amount', 12, 2)->default(0.00)->after('value');
            }
            if (!Schema::hasColumn('coupons', 'max_discount_amount')) {
                $table->decimal('max_discount_amount', 12, 2)->nullable()->after('min_order_amount');
            }
            if (!Schema::hasColumn('coupons', 'per_user_limit')) {
                $table->integer('per_user_limit')->nullable()->after('max_uses');
            }
            if (!Schema::hasColumn('coupons', 'selected_products')) {
                $table->json('selected_products')->nullable()->after('description');
            }
            if (!Schema::hasColumn('coupons', 'selected_categories')) {
                $table->json('selected_categories')->nullable()->after('selected_products');
            }
            if (!Schema::hasColumn('coupons', 'excluded_products')) {
                $table->json('excluded_products')->nullable()->after('selected_categories');
            }
            if (!Schema::hasColumn('coupons', 'excluded_categories')) {
                $table->json('excluded_categories')->nullable()->after('excluded_products');
            }
            if (!Schema::hasColumn('coupons', 'new_customers_only')) {
                $table->boolean('new_customers_only')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('coupons', 'free_shipping')) {
                $table->boolean('free_shipping')->default(false)->after('new_customers_only');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'min_order_amount',
                'max_discount_amount',
                'per_user_limit',
                'selected_products',
                'selected_categories',
                'excluded_products',
                'excluded_categories',
                'new_customers_only',
                'free_shipping',
            ]);
        });
    }
};
