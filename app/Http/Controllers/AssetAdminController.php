<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Very small controller that lets the admin manage assets:
 * - list existing assets
 * - create a new asset
 * - toggle active flag and adjust basic parameters
 */
class AssetAdminController extends Controller
{
    public function index(): View
    {
        $assets = Asset::orderBy('symbol')->get();

        return view('admin.assets.index', [
            'assets' => $assets,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'symbol' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:255'],
            'base_price' => ['required', 'numeric', 'min:0.01'],
            'volatility' => ['required', 'numeric', 'min:0'],
            'liquidity' => ['required', 'numeric', 'min:1'],
            'behaviour_profile' => ['required', 'string', 'max:50'],
            'is_crypto' => ['sometimes', 'boolean'],
        ]);

        $data['is_crypto'] = $request->boolean('is_crypto');
        $data['is_active'] = true;

        Asset::create($data);

        return redirect()->route('admin.assets.index')
            ->with('status', 'Asset created successfully.');
    }

    public function update(Request $request, int $assetId): RedirectResponse
    {
        $asset = Asset::findOrFail($assetId);

        $data = $request->validate([
            'base_price' => ['required', 'numeric', 'min:0.01'],
            'volatility' => ['required', 'numeric', 'min:0'],
            'liquidity' => ['required', 'numeric', 'min:1'],
            'behaviour_profile' => ['required', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $asset->update($data);

        return redirect()->route('admin.assets.index')
            ->with('status', 'Asset updated successfully.');
    }
}

