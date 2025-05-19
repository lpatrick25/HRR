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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('food_name', 100);
            $table->foreignId('food_category_id')->constrained('food_categories')->onDelete('cascade');
            $table->decimal('food_price', 10, 2);
            $table->enum('food_status', ['Available', 'Not Available'])->default('Available');
            $table->enum('food_unit', ['Piece', 'Slice', 'Serving', 'Platter', 'Plate', 'Set', 'Combo', 'Milliliter', 'Liter', 'Cup', 'Glass', 'Bottle', 'Can'])->default('Piece');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
