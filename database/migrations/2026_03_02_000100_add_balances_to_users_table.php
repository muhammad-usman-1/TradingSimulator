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
        Schema::table('users', function (Blueprint $table) {
            // Initial virtual cash and current cash balance for the simulator.
            $table->decimal('initial_balance', 15, 2)->default(1000.00)->after('role');
            $table->decimal('current_balance', 15, 2)->default(1000.00)->after('initial_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['initial_balance', 'current_balance']);
        });
    }
};

