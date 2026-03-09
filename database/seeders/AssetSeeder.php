<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Seed a small set of example assets with different behaviours.
     */
    public function run(): void
    {
        $assets = [
            [
                'symbol' => 'BLUE',
                'name' => 'Blue Chip Industries',
                'description' => 'A stable, low-volatility company for demonstrating safer trades.',
                'base_price' => 100.00,
                'volatility' => 0.0100,
                'liquidity' => 500000,
                'behaviour_profile' => 'stable',
                'is_crypto' => false,
                'is_active' => true,
            ],
            [
                'symbol' => 'TECH',
                'name' => 'Tech Growth Corp',
                'description' => 'Higher volatility technology stock with larger price swings.',
                'base_price' => 50.00,
                'volatility' => 0.0350,
                'liquidity' => 250000,
                'behaviour_profile' => 'growth',
                'is_crypto' => false,
                'is_active' => true,
            ],
            [
                'symbol' => 'REIT',
                'name' => 'City Real Estate Trust',
                'description' => 'Defensive real estate asset with moderate volatility.',
                'base_price' => 80.00,
                'volatility' => 0.0150,
                'liquidity' => 300000,
                'behaviour_profile' => 'defensive',
                'is_crypto' => false,
                'is_active' => true,
            ],
            [
                'symbol' => 'BTC',
                'name' => 'Simulated Bitcoin',
                'description' => 'Highly volatile crypto asset for demonstrating risk.',
                'base_price' => 30000.00,
                'volatility' => 0.0700,
                'liquidity' => 150000,
                'behaviour_profile' => 'crypto',
                'is_crypto' => true,
                'is_active' => true,
            ],
        ];

        foreach ($assets as $data) {
            Asset::updateOrCreate(
                ['symbol' => $data['symbol']],
                $data
            );
        }
    }
}

