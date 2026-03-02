<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * User model stripped of Laravel's built-in authentication features.
 *
 * We keep it as a plain Eloquent model so that all authentication logic
 * (password hashing, verification, roles) is implemented manually in
 * controllers/services.
 */
class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'password_salt',
        'role',
        'initial_balance',
        'current_balance',
    ];

    protected $hidden = [
        'password',
        'password_salt',
    ];
}

