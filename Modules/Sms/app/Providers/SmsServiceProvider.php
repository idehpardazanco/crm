<?php

namespace Modules\Sms\app\Providers;

use App\Support\BaseModuleServiceProvider;
use Modules\Sms\app\Services\SmsService;
use Modules\Sms\Contracts\SmsProviderInterface;
use Modules\Sms\app\Providers\PayamMatniSmsProvider;
// use Modules\Sms\app\Repositories\SmsRepository;

class SmsServiceProvider extends BaseModuleServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \Modules\Sms\Contracts\SmsProviderInterface::class,
            \Modules\Sms\app\Providers\PayamMatniSmsProvider::class
        );
    }

    public function boot(): void
    {
        $this->loadModule(__DIR__ . '/..');
    }
}