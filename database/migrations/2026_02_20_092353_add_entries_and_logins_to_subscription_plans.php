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
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->integer('max_entries')->nullable()->after('max_guards'); // Max entries per month (null = unlimited)
            $table->integer('max_gate_logins')->nullable()->after('max_entries'); // Max gate/guard logins (null = unlimited)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn(['max_entries', 'max_gate_logins']);
        });
    }
};
