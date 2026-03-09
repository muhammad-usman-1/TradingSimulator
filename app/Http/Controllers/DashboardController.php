<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Holding;
use App\Models\Portfolio;
use App\Models\User;
use App\Services\SimulationSessionManager;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private SimulationSessionManager $sessions;

    public function __construct()
    {
        $this->sessions = new SimulationSessionManager();
    }

    /**
     * User dashboard with portfolio summary, holdings, and active session info.
     */
    public function index(Request $request): View
    {
        $userId = $request->session()->get('user_id');
        $user = User::findOrFail($userId);

        $portfolio = Portfolio::where('user_id', $user->id)->first();
        $holdings = Holding::where('user_id', $user->id)
            ->with('asset')
            ->get();

        // Calculate current portfolio value using latest asset prices
        $totalValue = (float) $user->current_balance;
        $totalHoldingsValue = 0.0;
        $totalCost = 0.0;

        foreach ($holdings as $holding) {
            $asset = $holding->asset;
            $currentPrice = (float) $asset->base_price; // In real system, use PriceEngine
            $holdingValue = (float) $holding->quantity * $currentPrice;
            $totalHoldingsValue += $holdingValue;
            $totalCost += (float) $holding->quantity * (float) $holding->average_cost;
            $totalValue += $holdingValue;
        }

        // Calculate overall profit/loss from initial balance
        $overallPnL = $totalValue - (float) $user->initial_balance;
        $overallPnLPercent = $user->initial_balance > 0 
            ? ($overallPnL / (float) $user->initial_balance) * 100 
            : 0.0;

        // Unrealized P&L (from holdings)
        $unrealizedPnL = $totalHoldingsValue - $totalCost;

        $activeSession = $this->sessions->getOrCreateActiveSession($user);

        return view('dashboard.user', [
            'name' => $request->session()->get('user_name'),
            'user' => $user,
            'portfolio' => $portfolio,
            'holdings' => $holdings,
            'totalValue' => $totalValue,
            'totalHoldingsValue' => $totalHoldingsValue,
            'unrealizedPnL' => $unrealizedPnL,
            'overallPnL' => $overallPnL,
            'overallPnLPercent' => $overallPnLPercent,
            'activeSession' => $activeSession,
        ]);
    }

    /**
     * Admin dashboard placeholder showing that role-based access works.
     */
    public function admin(Request $request): View
    {
        return view('dashboard.admin', [
            'name' => $request->session()->get('user_name'),
        ]);
    }
}

