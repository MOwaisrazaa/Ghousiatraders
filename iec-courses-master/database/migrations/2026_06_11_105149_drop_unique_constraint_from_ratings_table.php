<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->index('user_id');
            $table->dropUnique('ratings_user_id_rateable_type_rateable_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->unique(['user_id', 'rateable_type', 'rateable_id']);
            $table->dropIndex(['user_id']);
        });
    }
};
