<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !Auth::user()->getRoleNames()->intersect($roles)->isNotEmpty()) {
            abort(403);
        }
        return $next($request);
    }
}