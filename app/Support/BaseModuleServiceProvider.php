<?php

namespace App\Support;

use Illuminate\Support\ServiceProvider;

/**
 * Base Service Provider for all modules
 * Standardizes module boot process
 */
abstract class BaseModuleServiceProvider extends ServiceProvider
{
    /**
     * Automatically load module routes, migrations, configs
     */
    protected function loadModule(string $modulePath): void
    {
        $routesPath = $modulePath . '/routes';

        if (file_exists($routesPath . '/web.php')) {
            $this->loadRoutesFrom($routesPath . '/web.php');
        }

        if (file_exists($routesPath . '/api.php')) {
            $this->loadRoutesFrom($routesPath . '/api.php');
        }

        if (is_dir($modulePath . '/database/migrations')) {
            $this->loadMigrationsFrom($modulePath . '/database/migrations');
        }
    }
}