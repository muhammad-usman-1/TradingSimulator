<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Holding;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Portfolio controller showing user's current holdings and trade history.
 */
class PortfolioController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->session()->get('user_id');
        $user = User::findOrFail($userId);

        $holdings = Holding::where('user_id', $user->id)
            ->with('asset')
            ->get();

        // Get recent trades
        $recentTrades = Trade::where('user_id', $user->id)
            ->with('asset')
            ->orderByDesc('executed_at')
            ->limit(20)
            ->get();

        // Calculate portfolio metrics
        $totalHoldingsValue = 0.0;
        $totalCost = 0.0;

        foreach ($holdings as $holding) {
            $asset = $holding->asset;
            $currentPrice = (float) $asset->base_price;
            $holdingValue = (float) $holding->quantity * $currentPrice;
            $totalHoldingsValue += $holdingValue;
            $totalCost += (float) $holding->quantity * (float) $holding->average_cost;
        }

        $unrealizedPnL = $totalHoldingsValue - $totalCost;
        $totalPortfolioValue = (float) $user->current_balance + $totalHoldingsValue;

        // Calculate overall profit/loss from initial balance
        $overallPnL = $totalPortfolioValue - (float) $user->initial_balance;
        $overallPnLPercent = $user->initial_balance > 0 
            ? ($overallPnL / (float) $user->initial_balance) * 100 
            : 0.0;

        // Calculate realized P&L from closed trades (sells)
        $realizedPnL = 0.0;
        foreach ($recentTrades as $trade) {
            if ($trade->side === 'sell') {
                // Find corresponding buy trades to calculate realized P&L
                // For simplicity, we'll use average cost from holdings
                $holding = $holdings->firstWhere('asset_id', $trade->asset_id);
                if ($holding) {
                    $avgCost = (float) $holding->average_cost;
                    $realizedPnL += ((float) $trade->price - $avgCost) * (float) $trade->quantity;
                }
            }
        }

        return view('dashboard.portfolio', [
            'user' => $user,
            'holdings' => $holdings,
            'recentTrades' => $recentTrades,
            'totalHoldingsValue' => $totalHoldingsValue,
            'totalCost' => $totalCost,
            'unrealizedPnL' => $unrealizedPnL,
            'realizedPnL' => $realizedPnL,
            'totalPortfolioValue' => $totalPortfolioValue,
            'overallPnL' => $overallPnL,
            'overallPnLPercent' => $overallPnLPercent,
        ]);
    }
}
