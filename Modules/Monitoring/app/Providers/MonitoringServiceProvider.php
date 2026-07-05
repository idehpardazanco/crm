<?php

namespace Modules\Monitoring\app\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class MonitoringServiceProvider extends ModuleServiceProvider
{

    protected string $name = 'Monitoring';

    protected string $nameLower = 'monitoring';

 
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

}