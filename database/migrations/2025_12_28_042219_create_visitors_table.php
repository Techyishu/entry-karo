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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('mobile_number')->unique()->comment('Unique identifier for visitor');
            $table->string('name');
            $table->string('address');
            $table->text('purpose')->comment('Purpose of visit');
            $table->string('vehicle_number')->nullable()->comment('Vehicle number if applicable');
            $table->string('photo_path')->nullable()->comment('Path to visitor photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
