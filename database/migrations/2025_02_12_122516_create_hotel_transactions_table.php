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
        Schema::create('hotel_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number', 100)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('customer_name', 100);
            $table->string('customer_number', 20);
            $table->string('customer_email', 100);
            $table->enum('customer_type', ['Registered', 'Walk-in', 'Online'])->default('Walk-in');
            $table->foreignId('hotel_room_id')->constrained('hotel_rooms')->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['Pending', 'Confirmed', 'Checked-in', 'Checked-out', 'Cancelled', 'No-show', 'Walk-in'])
                ->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_transactions');
    }
};
