<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If Auth session was lost after restart, clear everything and redirect to login
        if (!Auth::check() || !session('staff_logged_in')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired. Please log in again.',
            ]);
        }

        return $next($request);
    }
}