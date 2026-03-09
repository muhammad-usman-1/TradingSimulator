<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\Trade;
use Illuminate\View\View;

/**
 * Very small admin controller to demonstrate role management.
 *
 * Admin can see a list of all users and their roles/balances.
 */
class AdminController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('created_at')->get();
        $totalUsers = $users->count();
        $totalTrades = Trade::count();
        $totalAssets = Asset::count();
        $totalBalance = User::sum('current_balance');

        return view('admin.users', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'totalTrades' => $totalTrades,
            'totalAssets' => $totalAssets,
            'totalBalance' => $totalBalance,
        ]);
    }
}

