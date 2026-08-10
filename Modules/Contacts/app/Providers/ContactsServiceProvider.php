<?php

namespace Modules\Contacts\app\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Contacts\app\Models\Contact;
use Modules\Contacts\app\Observers\ContactObserver;
use Nwidart\Modules\Support\ModuleServiceProvider;

class ContactsServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name =
        'Contacts';

    /**
     * The lowercase version
     * of the module name.
     */
    protected string $nameLower =
        'contacts';

    /**
     * Provider classes
     * to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Boot module.
     */
    public function boot(): void
    {
        parent::boot();

        Contact::observe(
            ContactObserver::class
        );
    }

    /**
     * Define module schedules.
     */
    // protected function configureSchedules(
    //     Schedule $schedule
    // ): void {
    // }
}