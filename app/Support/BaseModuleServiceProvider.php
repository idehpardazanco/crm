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
        $this->loadRoutesFrom($modulePath . '/routes/web.php');
        $this->loadRoutesFrom($modulePath . '/routes/api.php');

        $this->loadMigrationsFrom($modulePath . '/database/migrations');
    }
}