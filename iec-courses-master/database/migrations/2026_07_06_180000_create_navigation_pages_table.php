<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('navigation_pages')) {
            Schema::create('navigation_pages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('link');
                $table->string('slug')->nullable()->unique();
                $table->string('type')->default('system'); // system or custom
                $table->longText('content')->nullable(); // for custom pages
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed default values
            DB::table('navigation_pages')->insert([
                [
                    'name' => 'HOME',
                    'link' => '/',
                    'slug' => null,
                    'type' => 'system',
                    'content' => null,
                    'order' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'MEN',
                    'link' => '/shop',
                    'slug' => null,
                    'type' => 'system',
                    'content' => null,
                    'order' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'WOMEN',
                    'link' => '/women',
                    'slug' => null,
                    'type' => 'system',
                    'content' => null,
                    'order' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'ATTARS',
                    'link' => '/attars',
                    'slug' => null,
                    'type' => 'system',
                    'content' => null,
                    'order' => 4,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'OUD',
                    'link' => '/oud',
                    'slug' => null,
                    'type' => 'system',
                    'content' => null,
                    'order' => 5,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'SIGNATURE',
                    'link' => '/#signature',
                    'slug' => null,
                    'type' => 'system',
                    'content' => null,
                    'order' => 6,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'SCENTED CANDLES',
                    'link' => '/scented-candles',
                    'slug' => null,
                    'type' => 'system',
                    'content' => null,
                    'order' => 7,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'CONTACT US',
                    'link' => '/contact',
                    'slug' => null,
                    'type' => 'system',
                    'content' => null,
                    'order' => 8,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_pages');
    }
};
