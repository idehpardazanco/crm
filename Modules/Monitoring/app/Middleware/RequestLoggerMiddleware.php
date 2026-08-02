<?php

namespace Modules\Monitoring\app\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Monitoring\app\Services\MonitoringService;

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
            'headers' => $request->headers->except([
                            'authorization',
                            'cookie',
                            'x-xsrf-token',
                        ]),
            'payload' => $request->except([
                            'password',
                            'password_confirmation',
                            'sms_password',
                            'token',
                            'code',
                        ]),
            'status_code' => $response->status(),
            'duration' => $duration,
        ]);

        return $response;
    }
}