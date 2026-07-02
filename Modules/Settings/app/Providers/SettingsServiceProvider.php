<?php

namespace Modules\Settings\Providers;

use App\Support\BaseModuleServiceProvider;
use Modules\Settings\Repositories\SettingRepository;
use Modules\Settings\Services\SettingService;

/**
 * Settings Module Provider
 */
class SettingsServiceProvider extends BaseModuleServiceProvider
{
    public function register(): void
    {
        // Bind Repository
        $this->app->singleton(
            SettingRepository::class,
            SettingRepository::class
        );

        // Bind Service
        $this->app->singleton(
            SettingService::class,
            SettingService::class
        );
    }

    public function boot(): void
    {
        $this->loadModule(__DIR__ . '/..');
    }
}