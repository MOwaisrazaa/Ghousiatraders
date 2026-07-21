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
        Schema::table('shoppingcarts', function (Blueprint $table) {
            $table->string('price_type')->nullable()->after('price');
            $table->decimal('original_price', 8, 2)->nullable()->after('price_type');
            $table->decimal('discount_amount', 8, 2)->default(0)->after('original_price');
            $table->text('discount_reason')->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shoppingcarts', function (Blueprint $table) {
            $table->dropColumn(['price_type', 'original_price', 'discount_amount', 'discount_reason']);
        });
    }
};
