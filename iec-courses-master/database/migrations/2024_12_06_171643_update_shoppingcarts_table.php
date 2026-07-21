<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('shoppingcarts', function (Blueprint $table) {
        $table->id();
        $table->integer('quantity')->default(1); // Quantity of the item in the cart
        $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade'); // Nullable course ID
        $table->foreignId('lecture_id')->nullable()->constrained('lectures')->onDelete('cascade'); // Nullable lecture ID
        $table->decimal('price', 8, 2); // Price for the selected course or lecture
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User associated with the cart
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
