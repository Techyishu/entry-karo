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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Basic Plan", "Premium Plan"
            $table->string('slug')->unique(); // e.g., "basic", "premium"
            $table->decimal('price', 10, 2); // Monthly price
            $table->text('description')->nullable();
            $table->integer('max_guards')->nullable(); // Max guards allowed (null = unlimited)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
