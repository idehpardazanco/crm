<?php

namespace Modules\Monitoring\app\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Monitoring\Services\MonitoringService;

class RequestLoggerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = (microtime(true) - $start) * 1000;

        app(MonitoringService::class)->request([
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'status_code' => $response->status(),
            'duration' => $duration,
        ]);

        return $response;
    }
}