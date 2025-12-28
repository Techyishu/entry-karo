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
        Schema::create('carry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries')->onDelete('cascade')->onUpdate('cascade');
            $table->string('item_name')->comment('Name of the item');
            $table->enum('item_type', ['personal', 'office', 'delivery', 'other'])->default('other')->comment('Type of item');
            $table->integer('quantity')->default(1)->comment('Quantity of items');
            $table->string('item_photo_path')->nullable()->comment('Path to item photo');
            $table->boolean('in_status')->default(true)->comment('Item was brought in');
            $table->boolean('out_status')->default(false)->comment('Item was taken out');
            $table->timestamps();

            // Index for faster lookups
            $table->index(['entry_id', 'item_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carry_items');
    }
};
