
<?php

namespace Modules\Monitoring\app\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class MonitoringServiceProvider extends ModuleServiceProvider
{
    //
     * The name of the module.
     *//
    protected string $name = 'Monitoring';

    /
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'monitoring';

    /
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

}