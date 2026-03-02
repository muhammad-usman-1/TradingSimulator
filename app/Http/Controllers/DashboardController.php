<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Simple user dashboard placeholder.
     *
     * Later this will be extended with portfolio, trades, and graphs.
     */
    public function index(Request $request): View
    {
        return view('dashboard.user', [
            'name' => $request->session()->get('user_name'),
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

