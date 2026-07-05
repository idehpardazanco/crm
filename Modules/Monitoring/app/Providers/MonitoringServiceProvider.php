<?php

namespace Modules\Monitoring\app\Providers;

use Illuminate\Support\ServiceProvider;

class MonitoringServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuthService::class);
        $this->app->singleton(OtpService::class);
    }

    public function boot(): void
    {
        $this->loadModule(__DIR__ . '/..');
    }
}