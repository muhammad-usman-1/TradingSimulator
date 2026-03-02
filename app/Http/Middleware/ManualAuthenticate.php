<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ManualAuthenticate replaces Laravel's built-in auth middleware.
 *
 * It only checks the custom session keys that we populate in AuthController,
 * keeping the authentication flow fully manual.
 */
class ManualAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login.show');
        }

        return $next($request);
    }
}

