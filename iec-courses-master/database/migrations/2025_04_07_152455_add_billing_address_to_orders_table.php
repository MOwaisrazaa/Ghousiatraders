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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('billing_address')->nullable()->after('status');
            $table->decimal('discount', 10, 2)->default(0)->after('total');
            $table->decimal('final_total', 10, 2)->after('discount');
            $table->string('coupon_code', 50)->nullable()->after('final_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['billing_address', 'discount', 'final_total', 'coupon_code']);
        });
    }
};
