<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not authenticated, let the auth middleware handle it
        if (! $user) {
            return $next($request);
        }

        // Check if user is banned
        if ($user->isBanned()) {
            Auth::logout();

            // Redirect to login page with error message
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Your account has been banned.',
                ]);
        }

        return $next($request);
    }
}
