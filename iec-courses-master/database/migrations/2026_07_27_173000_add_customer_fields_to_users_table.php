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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('active')->after('phone'); // active, inactive, blocked
            }
            if (!Schema::hasColumn('users', 'group')) {
                $table->string('group', 20)->default('regular')->after('status'); // regular, vip, new
            }
            if (!Schema::hasColumn('users', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('group');
            }
            if (!Schema::hasColumn('users', 'billing_address')) {
                $table->text('billing_address')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('users', 'notes')) {
                $table->text('notes')->nullable()->after('billing_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'group',
                'shipping_address',
                'billing_address',
                'notes',
            ]);
        });
    }
};
