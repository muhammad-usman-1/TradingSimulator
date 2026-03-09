<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Holding;
use App\Models\Trade;
use App\Models\User;
use App\Services\SimulationSessionManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Handles simple buy/sell operations for the practice market.
 *
 * This controller focuses on the core trading logic:
 * - basic validation
 * - updating user balances
 * - updating holdings
 * - recording a Trade entry
 */
class TradeController extends Controller
{
    private SimulationSessionManager $sessions;

    public function __construct()
    {
        $this->sessions = new SimulationSessionManager();
    }

    public function store(Request $request): RedirectResponse
    {
        // Enhanced validation with custom messages
        $validated = $request->validate([
            'asset_id' => ['required', 'integer', 'exists:assets,id'],
            'side' => ['required', 'in:buy,sell'],
            'quantity' => ['required', 'numeric', 'min:0.01', 'max:1000000'],
        ], [
            'asset_id.required' => 'Please select an asset to trade.',
            'asset_id.exists' => 'The selected asset does not exist.',
            'side.required' => 'Please specify whether you want to buy or sell.',
            'side.in' => 'Invalid trade type. Must be buy or sell.',
            'quantity.required' => 'Please enter a quantity.',
            'quantity.min' => 'Quantity must be at least 0.01.',
            'quantity.max' => 'Quantity cannot exceed 1,000,000.',
            'quantity.numeric' => 'Quantity must be a valid number.',
        ]);

        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login.show')
                ->with('status', 'Please log in to trade.');
        }

        try {
            /** @var User $user */
            $user = User::findOrFail($userId);
            /** @var Asset $asset */
            $asset = Asset::findOrFail((int) $validated['asset_id']);

            // Check if asset is active
            if (!$asset->is_active) {
                return redirect()
                    ->route('market.index')
                    ->with('status', 'This asset is currently inactive and cannot be traded.');
            }

        $side = $validated['side'];
        $quantity = (float) $validated['quantity'];

        // Use the asset's current base_price as our simulated "last price".
        $price = (float) $asset->base_price;
        $cost = $quantity * $price;

        $errorMessage = null;

        DB::transaction(function () use ($side, $quantity, $price, $cost, $user, $asset, &$errorMessage) {
            $session = $this->sessions->getOrCreateActiveSession($user);

            if ($side === 'buy') {
                if ($user->current_balance < $cost) {
                    $errorMessage = 'Not enough cash balance to complete this buy order.';
                    return;
                }

                $user->current_balance -= $cost;

                $holding = Holding::firstOrNew([
                    'user_id' => $user->id,
                    'asset_id' => $asset->id,
                ]);

                $oldQuantity = (float) $holding->quantity;
                $oldAverage = (float) $holding->average_cost;

                $newQuantity = $oldQuantity + $quantity;

                if ($newQuantity <= 0) {
                    $newAverage = 0.0;
                } elseif ($oldQuantity <= 0) {
                    $newAverage = $price;
                } else {
                    $newAverage = (($oldQuantity * $oldAverage) + ($quantity * $price)) / $newQuantity;
                }

                $holding->quantity = $newQuantity;
                $holding->average_cost = $newAverage;

                $holding->save();
                $user->save();
            } else {
                // side === 'sell'
                $holding = Holding::where('user_id', $user->id)
                    ->where('asset_id', $asset->id)
                    ->first();

                if (!$holding || $holding->quantity < $quantity) {
                    $errorMessage = 'You do not own enough of this asset to sell that quantity.';
                    return;
                }

                $holding->quantity = $holding->quantity - $quantity;
                $holding->save();

                $user->current_balance += $cost;
                $user->save();
            }

            if ($errorMessage === null) {
                $trade = Trade::create([
                    'user_id' => $user->id,
                    'asset_id' => $asset->id,
                    'side' => $side,
                    'quantity' => $quantity,
                    'price' => $price,
                    'simulation_session_id' => $session->id,
                    'status' => 'executed',
                    'executed_at' => now(),
                ]);

                // Keep the session's balance in sync with the user for reporting.
                $this->sessions->syncBalance($session, $user);
            }
        });

        if ($errorMessage !== null) {
            return redirect()
                ->route('market.index')
                ->with('status', $errorMessage);
        }

        $message = $side === 'buy'
            ? 'Buy order executed successfully.'
            : 'Sell order executed successfully.';

        return redirect()
            ->route('market.index')
            ->with('status', $message);
        } catch (\Exception $e) {
            // Log error in production; for now just show generic message
            return redirect()
                ->route('market.index')
                ->with('status', 'An error occurred while processing your trade. Please try again.');
        }
    }
}

