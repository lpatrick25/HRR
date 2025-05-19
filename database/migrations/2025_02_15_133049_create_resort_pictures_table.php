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
        Schema::create('resort_pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resort_cottage_id')->constrained('resort_cottages')->onDelete('cascade');
            $table->text('picture');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resort_pictures');
    }
};
