<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Candle;
use App\Support\Algorithms\BinarySearch;
use App\Support\Algorithms\MergeSort;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller to demonstrate manual merge sort and binary search over candle data.
 *
 * This will be useful for the report because it clearly shows where the
 * algorithms are used in the real application.
 */
class MarketHistoryController extends Controller
{
    public function show(Request $request, int $assetId): View
    {
        $asset = Asset::findOrFail($assetId);

        // Retrieve raw candle data as an array of plain values.
        $candles = Candle::where('asset_id', $asset->id)->get()->map(function (Candle $candle) {
            return [
                'time_index' => (int) $candle->time_index,
                'open' => (float) $candle->open,
                'high' => (float) $candle->high,
                'low' => (float) $candle->low,
                'close' => (float) $candle->close,
                'volume' => (float) $candle->volume,
            ];
        })->all();

        $sorter = new MergeSort();
        $search = new BinarySearch();

        // Use our own merge sort to order candles by time_index.
        $sorted = $sorter->sortByKey($candles, 'time_index', true);

        // Example use of binary search: find a candle at a specific index if provided.
        $selectedCandle = null;
        $selectedIndex = null;

        if ($request->filled('time_index')) {
            $selectedIndex = (int) $request->query('time_index');
            $foundPosition = $search->findIndexByKey($sorted, 'time_index', $selectedIndex);

            if ($foundPosition !== -1) {
                $selectedCandle = $sorted[$foundPosition];
            }
        }

        return view('market.history', [
            'asset' => $asset,
            'sortedCandles' => $sorted,
            'selectedCandle' => $selectedCandle,
            'selectedIndex' => $selectedIndex,
        ]);
    }

    /**
     * Return recent candles for an asset as JSON for the candlestick chart.
     */
    public function json(Request $request, int $assetId): JsonResponse
    {
        $asset = Asset::findOrFail($assetId);

        $candles = Candle::where('asset_id', $asset->id)->get()->map(function (Candle $candle) {
            return [
                'time_index' => (int) $candle->time_index,
                'open' => (float) $candle->open,
                'high' => (float) $candle->high,
                'low' => (float) $candle->low,
                'close' => (float) $candle->close,
                'volume' => (float) $candle->volume,
            ];
        })->all();

        $sorter = new MergeSort();
        $sorted = $sorter->sortByKey($candles, 'time_index', true);

        return response()->json([
            'asset' => [
                'id' => $asset->id,
                'symbol' => $asset->symbol,
                'name' => $asset->name,
            ],
            'candles' => $sorted,
        ]);
    }
}

