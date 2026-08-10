<?php

namespace Modules\Sms\app\Providers;

use Modules\Sms\app\Contracts\SmsProviderInterface;
use Nwidart\Modules\Support\ModuleServiceProvider;

class SmsServiceProvider extends ModuleServiceProvider
{
    protected string $name =
        'Sms';

    protected string $nameLower =
        'sms';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->bind(
            SmsProviderInterface::class,
            PayamMatniSmsProvider::class
        );
    }
}