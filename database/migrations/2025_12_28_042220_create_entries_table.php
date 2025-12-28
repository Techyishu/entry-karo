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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('guard_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->dateTime('in_time')->comment('Check-in time');
            $table->dateTime('out_time')->nullable()->comment('Check-out time');
            $table->integer('duration_minutes')->nullable()->comment('Total visit duration in minutes');
            $table->timestamps();

            // Indexes for better query performance
            $table->index('in_time');
            $table->index('out_time');
            $table->index(['visitor_id', 'guard_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
