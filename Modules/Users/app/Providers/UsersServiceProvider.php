<?php

namespace Modules\Users\app\Providers;

use App\Support\BaseModuleServiceProvider;
use Modules\Users\Services\UserService;
use Modules\Users\Repositories\UserRepository;

class UsersServiceProvider extends BaseModuleServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserRepository::class);
        $this->app->singleton(UserService::class);
    }

    public function boot(): void
    {
        $this->loadModule(__DIR__ . '/..');
    }
}