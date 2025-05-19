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
        Schema::create('resort_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number', 100)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('customer_name', 50);
            $table->string('customer_number', 20);
            $table->string('customer_email', 50);
            $table->enum('customer_type', ['Registered', 'Walk-in', 'Online'])->default('Walk-in');
            $table->foreignId('resort_cottage_id')->constrained('resort_cottages')->onDelete('cascade');
            $table->date('booking_date');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'No-show'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resort_transactions');
    }
};
