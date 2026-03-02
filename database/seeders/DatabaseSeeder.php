<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\PasswordHasher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $hasher = new PasswordHasher();
        $hashed = $hasher->hash('admin123'); // default admin password for testing

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => $hashed['hash'],
                'password_salt' => $hashed['salt'],
                'role' => 'admin',
                'initial_balance' => 1000.00,
                'current_balance' => 1000.00,
            ]
        );
    }
}
