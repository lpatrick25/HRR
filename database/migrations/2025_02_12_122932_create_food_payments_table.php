<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_payments', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('food_transaction_id')->constrained('food_transactions')->onDelete('cascade'); // Reference to the food transaction
            $table->enum('payment_method', ['Cash', 'Online'])->default('Online');
            $table->decimal('total_amount', 10, 2)->unsigned();
            $table->decimal('amount_paid', 10, 2)->unsigned();
            $table->string('checkout_session_id', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_payments');
    }
};
