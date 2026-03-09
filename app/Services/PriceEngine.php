<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Candle;
use App\Models\Trade;
use App\Support\Collections\SimpleQueue;
use Illuminate\Support\Facades\DB;

/**
 * Core price engine for the trading simulator.
 *
 * Responsibilities:
 * - Maintain a MarketState per asset.
 * - On each "tick", apply random noise and queued user trades to adjust price.
 * - Append tick prices to the current candle and periodically close candles.
 *
 * This class intentionally avoids third-party helpers and uses simple
 * arithmetic and custom data structures (SimpleQueue, MarketState).
 */
class PriceEngine
{
    /**
     * @var array<int, MarketState> keyed by asset id
     */
    private array $marketStates = [];

    /**
     * Queue of trades waiting to be applied on the next tick.
     */
    private SimpleQueue $tradeQueue;

    /**
     * Number of ticks per candle (can later be made configurable).
     */
    private int $ticksPerCandle = 5;

    public function __construct()
    {
        $this->tradeQueue = new SimpleQueue();
    }

    /**
     * Ensure we have a MarketState instance for the given asset.
     */
    public function getStateForAsset(Asset $asset): MarketState
    {
        if (!isset($this->marketStates[$asset->id])) {
            $this->marketStates[$asset->id] = new MarketState($asset);
        }

        return $this->marketStates[$asset->id];
    }

    /**
     * Add a trade to be processed on the next tick.
     */
    public function queueTrade(Trade $trade): void
    {
        $this->tradeQueue->enqueue($trade);
    }

    /**
     * Advance the simulation by one tick for all active assets.
     *
     * @return array<int, float> latest prices keyed by asset id
     */
    public function tick(): array
    {
        $latestPrices = [];

        // Fetch all active assets; in a bigger system you might select a subset.
        $assets = Asset::where('is_active', true)->get();

        foreach ($assets as $asset) {
            $state = $this->getStateForAsset($asset);

            $noiseImpact = $this->calculateNoiseImpact($asset, $state->currentPrice);
            $tradeImpact = $this->consumeTradeImpactForAsset($asset, $state->currentPrice);

            $newPrice = $state->currentPrice + $noiseImpact + $tradeImpact;

            // Prevent prices from going negative or to zero.
            if ($newPrice < 0.01) {
                $newPrice = 0.01;
            }

            $state->currentPrice = $newPrice;
            $state->currentCandleTicks->add($newPrice);

            // If we have enough ticks, close a candle.
            if ($state->currentCandleTicks->size() >= $this->ticksPerCandle) {
                $this->closeCandle($state);
            }

            $latestPrices[$asset->id] = $newPrice;
        }

        return $latestPrices;
    }

    /**
     * Calculate random market noise using the asset's volatility setting.
     *
     * This uses a very simple random-walk style algorithm:
     *  - pick a random percentage move in the range [-volatility, +volatility]
     *  - multiply by current price to get the absolute impact.
     */
    private function calculateNoiseImpact(Asset $asset, float $currentPrice): float
    {
        $vol = (float) $asset->volatility;

        // Random integer between -1000 and 1000, scaled back to [-1, 1].
        $raw = mt_rand(-1000, 1000) / 1000.0;

        $percentageChange = $raw * $vol;

        return $currentPrice * $percentageChange;
    }

    /**
     * Consume all queued trades for a specific asset and calculate the price impact.
     *
     * The more volume relative to liquidity, the stronger the price move.
     */
    private function consumeTradeImpactForAsset(Asset $asset, float $currentPrice): float
    {
        $impact = 0.0;
        $liquidity = (float) $asset->liquidity;

        if ($liquidity <= 0) {
            $liquidity = 1.0;
        }

        // We pull out trades from the global queue one by one and apply those
        // that match the given asset.
        $remaining = new SimpleQueue();

        while (!$this->tradeQueue->isEmpty()) {
            /** @var Trade $trade */
            $trade = $this->tradeQueue->dequeue();

            if ($trade->asset_id !== $asset->id) {
                // Not for this asset; keep it for later.
                $remaining->enqueue($trade);
                continue;
            }

            $direction = $trade->side === 'buy' ? 1.0 : -1.0;
            $quantity = (float) $trade->quantity;

            // Simple linear impact model: bigger orders move price more.
            $volumeRatio = $quantity / $liquidity;
            $impact += $direction * $currentPrice * $volumeRatio;
        }

        // Restore trades that were for other assets.
        while (!$remaining->isEmpty()) {
            $this->tradeQueue->enqueue($remaining->dequeue());
        }

        return $impact;
    }

    /**
     * Close the current candle for a market state, compute OHLCV, store it,
     * and start a new candle.
     */
    private function closeCandle(MarketState $state): void
    {
        $tickCount = $state->currentCandleTicks->size();

        if ($tickCount === 0) {
            return;
        }

        $open = (float) $state->currentCandleTicks->get(0);
        $high = $open;
        $low = $open;
        $close = (float) $state->currentCandleTicks->last();

        // Compute high/low by scanning the list manually.
        for ($i = 0; $i < $tickCount; $i++) {
            $price = (float) $state->currentCandleTicks->get($i);

            if ($price > $high) {
                $high = $price;
            }

            if ($price < $low) {
                $low = $price;
            }
        }

        // Volume will be calculated more precisely later; for now we store zero.
        $volume = 0.0;

        DB::transaction(function () use ($state, $open, $high, $low, $close, $volume, $tickCount) {
            Candle::create([
                'asset_id' => $state->asset->id,
                'time_index' => $state->nextTimeIndex,
                'timeframe_seconds' => 60,
                'open' => $open,
                'high' => $high,
                'low' => $low,
                'close' => $close,
                'volume' => $volume,
                'driver' => 'mixed',
                'formed_at' => now(),
            ]);
        });

        // Record in-memory for quick access and trim to a reasonable size.
        $state->recentCandles->add([
            'time_index' => $state->nextTimeIndex,
            'open' => $open,
            'high' => $high,
            'low' => $low,
            'close' => $close,
        ]);

        if ($state->recentCandles->size() > 200) {
            // Drop the oldest candle by removing index 0.
            $state->recentCandles->removeAt(0);
        }

        $state->nextTimeIndex++;

        // Start a new candle: reset tick list.
        $state->currentCandleTicks = new \App\Support\Collections\SimpleList();
    }
}

