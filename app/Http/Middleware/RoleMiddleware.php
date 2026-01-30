<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Check if user's role matches the required role
        if (Auth::user()->role !== $role) {
            abort(403, 'UNAUTHORIZED ACCESS: You do not have permission to view this page.');
        }

        return $next($request);
    }
}