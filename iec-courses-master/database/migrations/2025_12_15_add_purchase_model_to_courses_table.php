<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('purchase_model', ['flexible', 'restricted'])
                  ->default('flexible')
                  ->after('is_free')
                  ->comment('flexible: can buy whole course or individual lectures, restricted: whole course only');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('purchase_model');
        });
    }
};
