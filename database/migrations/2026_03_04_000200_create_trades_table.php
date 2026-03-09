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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');

            $table->string('side'); // 'buy' or 'sell'
            $table->decimal('quantity', 18, 4);
            $table->decimal('price', 15, 4);

            // Links to simulation session (optional for now).
            $table->unsignedBigInteger('simulation_session_id')->nullable();

            $table->string('status')->default('executed'); // 'executed', 'pending', 'cancelled'
            $table->timestamp('executed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'asset_id']);
            $table->index('simulation_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};

