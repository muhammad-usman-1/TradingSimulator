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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            // Core pricing behaviour
            $table->decimal('base_price', 15, 2);
            $table->decimal('volatility', 8, 4)->default(0.02); // percentage-ish, e.g. 0.02 = 2%
            $table->decimal('liquidity', 15, 2)->default(100000); // how much volume before big impact

            // Simple behaviour profile label (e.g. "stable", "growth", "crypto")
            $table->string('behaviour_profile')->default('neutral');

            $table->boolean('is_crypto')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};

