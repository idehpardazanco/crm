<?php

namespace Modules\Sms\app\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Modules\Sms\app\Contracts\SmsProviderInterface;
use Modules\Sms\app\Providers\PayamMatniSmsProvider;

class SmsServiceProvider extends ModuleServiceProvider
{
    
    public function register(): void
    {
        $this->app->bind(SmsProviderInterface::class, PayamMatniSmsProvider::class);
    }
    
    /**
     * The name of the module.
     */
    protected string $name = 'Sms';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'sms';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
