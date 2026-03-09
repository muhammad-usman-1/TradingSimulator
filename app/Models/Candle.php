<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candle extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'time_index',
        'timeframe_seconds',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'driver',
        'formed_at',
    ];

    protected $casts = [
        'formed_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}

