<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMonitoringAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403);
        }

        if (!auth()->user()->can('view monitoring')) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
