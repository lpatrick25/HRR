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
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name', 100);
            $table->foreignId('hotel_type_id')->constrained('hotel_types')->onDelete('cascade');
            $table->enum('room_status', ['Available', 'Maintenance'])->default('Available');
            $table->decimal('room_rate', 10, 2);
            $table->integer('room_capacity');
            $table->text('picture')->default('img/profile/1.jpg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
