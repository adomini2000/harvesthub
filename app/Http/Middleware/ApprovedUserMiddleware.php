<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApprovedUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin always has access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Buyers are auto-approved, always have access
        if ($user->isBuyer()) {
            return $next($request);
        }

        // Sellers and Riders need approval
        if (!$user->isApproved()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('warning', 'Your account is pending admin approval.');
        }

        return $next($request);
    }
}
