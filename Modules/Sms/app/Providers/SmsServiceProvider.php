<?php

namespace Modules\Sms\app\Providers;

use App\Support\BaseModuleServiceProvider;
use Modules\Sms\app\Contracts\SmsProviderInterface;

class SmsServiceProvider extends BaseModuleServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            SmsProviderInterface::class,
            PayamMatniSmsProvider::class
        );
    }

    public function boot(): void
    {
        $this->loadModule(
            dirname(__DIR__, 2)
        );
    }
}