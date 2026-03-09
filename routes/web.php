<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MarketHistoryController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\AssetAdminController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SessionSummaryController;
use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.show');
});

// Guest routes: registration and login
Route::middleware([])->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

// Authenticated user routes
Route::middleware(['auth.manual'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/market', [MarketController::class, 'index'])->name('market.index');
    Route::get('/market/charts', [MarketController::class, 'charts'])->name('market.charts');
    Route::get('/market/tick', [MarketController::class, 'tick'])->name('market.tick');
    Route::get('/market/history/{asset}', [MarketHistoryController::class, 'show'])->name('market.history');
    Route::get('/market/history/{asset}/json', [MarketHistoryController::class, 'json'])->name('market.history.json');
    Route::get('/market/chart/{asset}', function (\App\Models\Asset $asset) {
        return view('market.chart', ['asset' => $asset]);
    })->name('market.chart');
    Route::post('/market/trade', [TradeController::class, 'store'])->name('market.trade');
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/session/summary', [SessionSummaryController::class, 'show'])->name('session.summary');
    Route::post('/session/complete', [SessionSummaryController::class, 'complete'])->name('session.complete');
    Route::get('/tutorial', [TutorialController::class, 'index'])->name('tutorial.index');
    Route::get('/tutorial/step/{step}', [TutorialController::class, 'show'])->name('tutorial.step');
    Route::post('/tutorial/step/{step}/complete', [TutorialController::class, 'complete'])->name('tutorial.complete');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin-only routes
Route::middleware(['auth.manual', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::get('/admin/assets', [AssetAdminController::class, 'index'])->name('admin.assets.index');
    Route::post('/admin/assets', [AssetAdminController::class, 'store'])->name('admin.assets.store');
    Route::put('/admin/assets/{asset}', [AssetAdminController::class, 'update'])->name('admin.assets.update');
});

