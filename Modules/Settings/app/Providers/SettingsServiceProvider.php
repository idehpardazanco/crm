<?php

namespace Modules\Settings\app\Providers;

use App\Support\BaseModuleServiceProvider;
use Modules\Settings\app\Repositories\SettingsRepository;
use Modules\Settings\app\Services\SettingsService;

/**
 * Settings Module Provider
 */
class SettingsServiceProvider extends BaseModuleServiceProvider
{
    public function register(): void
    {
        // Bind Repository
        $this->app->singleton(
            SettingsRepository::class,
            SettingsRepository::class
        );

        // Bind Service
        $this->app->singleton(
            SettingsService::class,
            SettingsService::class
        );
    }

    public function boot(): void
    {
        $this->loadModule(__DIR__ . '/..');
    }
}