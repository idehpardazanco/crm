<?php

namespace Modules\Sms\app\Providers;

use App\Support\BaseModuleServiceProvider;
use Modules\Sms\Services\SmsService;
use Modules\Sms\Repositories\SmsRepository;

class SmsServiceProvider extends BaseModuleServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SmsService::class);
        $this->app->singleton(SmsRepository::class);
    }

    public function boot(): void
    {
        $this->loadModule(__DIR__ . '/..');
    }
}