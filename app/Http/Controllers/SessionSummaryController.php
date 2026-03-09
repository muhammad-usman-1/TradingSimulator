<?php

namespace App\Http\Controllers;

use App\Models\SimulationSession;
use App\Models\Trade;
use App\Models\User;
use App\Services\SimulationSessionManager;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Builds a simple performance summary for the user's current simulation session.
 */
class SessionSummaryController extends Controller
{
    private SimulationSessionManager $sessions;

    public function __construct()
    {
        $this->sessions = new SimulationSessionManager();
    }

    public function show(Request $request): View
    {
        $userId = $request->session()->get('user_id');

        /** @var User $user */
        $user = User::findOrFail($userId);

        $session = $this->sessions->getOrCreateActiveSession($user);

        $trades = Trade::where('user_id', $user->id)
            ->where('simulation_session_id', $session->id)
            ->orderBy('executed_at')
            ->get();

        $totalTrades = $trades->count();
        $buyTrades = $trades->where('side', 'buy')->count();
        $sellTrades = $trades->where('side', 'sell')->count();
        $totalQuantity = $trades->sum('quantity');

        $netCashFlow = 0.0;
        foreach ($trades as $trade) {
            $amount = (float) $trade->price * (float) $trade->quantity;
            if ($trade->side === 'buy') {
                $netCashFlow -= $amount;
            } else {
                $netCashFlow += $amount;
            }
        }

        $averageTradeSize = $totalTrades > 0 ? $totalQuantity / $totalTrades : 0.0;

        $profitLoss = (float) $session->current_balance - (float) $session->starting_balance;

        return view('dashboard.session-summary', [
            'session' => $session,
            'totalTrades' => $totalTrades,
            'buyTrades' => $buyTrades,
            'sellTrades' => $sellTrades,
            'totalQuantity' => $totalQuantity,
            'averageTradeSize' => $averageTradeSize,
            'netCashFlow' => $netCashFlow,
            'profitLoss' => $profitLoss,
            'trades' => $trades,
        ]);
    }

    /**
     * Complete/end the current active session.
     */
    public function complete(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $user = User::findOrFail($userId);

        $session = $this->sessions->completeSession($user);

        if ($session) {
            return redirect()
                ->route('session.summary')
                ->with('status', 'Session completed successfully. Review your performance below.');
        }

        return redirect()
            ->route('dashboard')
            ->with('status', 'No active session to complete.');
    }
}

