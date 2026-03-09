<?php

namespace App\Services;

use App\Models\Asset;
use App\Support\Collections\SimpleList;

/**
 * MarketState keeps track of in-memory price and tick history for a single asset.
 *
 * This class deliberately uses the custom SimpleList structure to show list
 * operations and give the price engine a clean interface.
 */
class MarketState
{
    public Asset $asset;

    /**
     * Most recent price for this asset.
     */
    public float $currentPrice;

    /**
     * All tick prices for the current, not-yet-closed candle.
     */
    public SimpleList $currentCandleTicks;

    /**
     * Recent closed candles (in memory) for quick access by the chart.
     */
    public SimpleList $recentCandles;

    /**
     * Sequential index used when forming new candles.
     */
    public int $nextTimeIndex = 0;

    public function __construct(Asset $asset)
    {
        $this->asset = $asset;
        $this->currentPrice = (float) $asset->base_price;
        $this->currentCandleTicks = new SimpleList();
        $this->recentCandles = new SimpleList();
    }
}

