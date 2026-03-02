<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Simple portfolio record: one per user.
 *
 * This will later be extended with more complex calculations,
 * but for now it just tracks high-level invested amount and P&L.
 */
class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_invested',
        'total_profit_loss',
    ];
}

