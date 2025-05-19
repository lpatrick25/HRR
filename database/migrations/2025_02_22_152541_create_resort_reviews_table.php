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
        Schema::create('resort_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Authenticated user
            $table->foreignId('resort_cottage_id')->constrained('resort_cottages')->onDelete('cascade');
            $table->text('review');
            $table->integer('rating')->default(5); // 1-5 star rating
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending'); // Moderation status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resort_reviews');
    }
};
