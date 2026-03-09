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
        Schema::create('candles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');

            // Sequential index within the simulation plus an optional real timestamp.
            $table->unsignedBigInteger('time_index');
            $table->unsignedInteger('timeframe_seconds')->default(60);

            $table->decimal('open', 15, 4);
            $table->decimal('high', 15, 4);
            $table->decimal('low', 15, 4);
            $table->decimal('close', 15, 4);
            $table->decimal('volume', 18, 4)->default(0);

            // Optional meta info: whether this candle was mostly driven by user trades or noise.
            $table->string('driver')->default('mixed'); // 'user', 'noise', or 'mixed'

            $table->timestamp('formed_at')->nullable();
            $table->timestamps();

            $table->unique(['asset_id', 'time_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candles');
    }
};

