<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'description',
        'base_price',
        'volatility',
        'liquidity',
        'behaviour_profile',
        'is_crypto',
        'is_active',
    ];

    public function candles()
    {
        return $this->hasMany(Candle::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function holdings()
    {
        return $this->hasMany(Holding::class);
    }
}

