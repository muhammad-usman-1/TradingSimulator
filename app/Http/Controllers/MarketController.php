<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\PriceEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Simple market controller that exposes:
 * - a live prices page for users
 * - a JSON endpoint that advances the price engine one tick
 */
class MarketController extends Controller
{
    private PriceEngine $engine;

    public function __construct()
    {
        $this->engine = new PriceEngine();
    }

    /**
     * Show the live market screen with a table of assets.
     */
    public function index(Request $request): View
    {
        $assets = Asset::where('is_active', true)->get();

        return view('market.live', [
            'assets' => $assets,
        ]);
    }

    /**
     * Show a charts hub page listing assets, linking to their trading graphs.
     */
    public function charts(Request $request): View
    {
        $assets = Asset::where('is_active', true)->orderBy('symbol')->get();

        return view('market.charts', [
            'assets' => $assets,
        ]);
    }

    /**
     * Advance the simulation one tick and return latest prices as JSON.
     */
    public function tick(Request $request): JsonResponse
    {
        $latest = $this->engine->tick();

        return response()->json([
            'prices' => $latest,
        ]);
    }
}

