<?php

namespace Modules\Auth\app\Providers;

use App\Support\BaseModuleServiceProvider;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Services\OtpService;

class AuthServiceProvider extends BaseModuleServiceProvider
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