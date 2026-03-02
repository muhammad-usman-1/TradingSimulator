<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureUserHasRole enforces simple role-based access using our
 * custom session keys. No Laravel Gate/Policy or built-in auth.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $currentRole = (string) $request->session()->get('user_role', '');

        if ($currentRole !== $role) {
            abort(403, 'You are not authorised to access this area.');
        }

        return $next($request);
    }
}

