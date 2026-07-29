<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        abort_if(! $request->user() || $request->user()->role?->name !== $role, 403);

        return $next($request);
    }
}
