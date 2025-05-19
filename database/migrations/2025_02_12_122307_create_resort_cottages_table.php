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
        Schema::create('resort_cottages', function (Blueprint $table) {
            $table->id();
            $table->string('cottage_name', 100);
            $table->foreignId('resort_type_id')->constrained('resort_types')->onDelete('cascade');
            $table->enum('cottage_status', ['Available', 'Maintenance'])->default('Available');
            $table->decimal('cottage_rate', 10, 2);
            $table->integer('cottage_capacity');
            $table->text('picture')->default('img/profile/1.jpg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resort_cottages');
    }
};
