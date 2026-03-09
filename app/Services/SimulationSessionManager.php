<?php

namespace App\Services;

use App\Models\SimulationSession;
use App\Models\User;

/**
 * Helper service to manage the active simulation session per user.
 *
 * It keeps the logic for creating or finding a session in one place so that
 * controllers can simply ask for the "current" session.
 */
class SimulationSessionManager
{
    /**
     * Get the active session for a user or create one if none exists.
     */
    public function getOrCreateActiveSession(User $user): SimulationSession
    {
        $session = SimulationSession::where('user_id', $user->id)
            ->whereNull('ended_at')
            ->orderByDesc('started_at')
            ->first();

        if ($session) {
            return $session;
        }

        return SimulationSession::create([
            'user_id' => $user->id,
            'starting_balance' => $user->current_balance,
            'current_balance' => $user->current_balance,
            'mode' => 'standard',
            'started_at' => now(),
        ]);
    }

    /**
     * Update the session's current balance to mirror the user's current balance.
     */
    public function syncBalance(SimulationSession $session, User $user): void
    {
        $session->current_balance = $user->current_balance;
        $session->save();
    }

    /**
     * End/complete the active session for a user.
     */
    public function completeSession(User $user): ?SimulationSession
    {
        $session = SimulationSession::where('user_id', $user->id)
            ->whereNull('ended_at')
            ->orderByDesc('started_at')
            ->first();

        if ($session) {
            $session->current_balance = $user->current_balance;
            $session->ended_at = now();
            $session->save();
        }

        return $session;
    }
}

