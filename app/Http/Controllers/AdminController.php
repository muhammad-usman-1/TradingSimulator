<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        // Simple list retrieval; more complex sorting/algorithms will be added later.
        $users = User::orderBy('created_at')->get();

        return view('admin.users', [
            'users' => $users,
        ]);
    }
}

