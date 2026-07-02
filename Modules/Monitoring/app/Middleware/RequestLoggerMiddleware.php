<?php

namespace Modules\Monitoring\app\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Monitoring\Models\RequestLog;

/**
 * Logs every HTTP request in system
 */
class RequestLoggerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = (microtime(true) - $start) * 1000;

        RequestLog::create([
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => auth()->id(),

            'headers' => $request->headers->all(),
            'payload' => $request->all(),

            'status_code' => $response->status(),
            'duration' => (int) $duration,
        ]);

        return $response;
    }
}