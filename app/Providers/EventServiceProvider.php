<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // فعلاً خالی - چون ما از event() مستقیم استفاده می‌کنیم
        \Modules\Monitoring\App\Events\ErrorOccurred::class => [],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be auto discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}