<?php

namespace Modules\Settings\app\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class SettingsServiceProvider extends ModuleServiceProvider
{
    protected string $name =
        'Settings';

    protected string $nameLower =
        'settings';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}