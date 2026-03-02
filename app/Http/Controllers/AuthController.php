<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Services\PasswordHasher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request with manual validation and hashing.
     */
    public function register(Request $request): RedirectResponse|View
    {
        // Manual validation using simple array/list operations
        $errors = [];

        $name = trim((string) $request->input('name', ''));
        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');
        $passwordConfirmation = (string) $request->input('password_confirmation', '');

        if (strlen($name) < 3) {
            $errors[] = 'Name must be at least 3 characters.';
        }

        if (!preg_match('/^[\w.\-]+@[\w\-]+\.[A-Za-z]{2,}$/', $email)) {
            $errors[] = 'Email format is invalid.';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($password !== $passwordConfirmation) {
            $errors[] = 'Password confirmation does not match.';
        }

        if (User::where('email', $email)->exists()) {
            $errors[] = 'An account with this email already exists.';
        }

        if (!empty($errors)) {
            // Return the view directly instead of using Laravel's Validator
            return view('auth.register', [
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);
        }

        $hasher = new PasswordHasher();
        $hashed = $hasher->hash($password);

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $hashed['hash'];
        $user->password_salt = $hashed['salt'];
        $user->role = 'user';
        // Starting budget for every new trader, as per proposal.
        $user->initial_balance = 1000.00;
        $user->current_balance = 1000.00;
        $user->save();

        // Create an empty portfolio record for this new user.
        Portfolio::create([
            'user_id' => $user->id,
            'total_invested' => 0,
            'total_profit_loss' => 0,
        ]);

        return redirect()->route('login.show')->with('status', 'Registration successful. Please log in.');
    }

    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle a login attempt using our manual hasher and session logic.
     */
    public function login(Request $request): RedirectResponse|View
    {
        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return view('auth.login', [
                'error' => 'Invalid credentials.',
                'old' => ['email' => $email],
            ]);
        }

        $hasher = new PasswordHasher();

        if (!$hasher->verify($password, $user->password, $user->password_salt)) {
            return view('auth.login', [
                'error' => 'Invalid credentials.',
                'old' => ['email' => $email],
            ]);
        }

        // Manual "auth": store only the necessary data in the session
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_role', $user->role);
        $request->session()->put('user_name', $user->name);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Log the user out by clearing our custom session keys.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['user_id', 'user_role', 'user_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show');
    }
}

