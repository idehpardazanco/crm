<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Modules\FollowUps\app\Services\FollowUpReminderService;


/*
|--------------------------------------------------------------------------
| Inspire
|--------------------------------------------------------------------------
*/

Artisan::command(
    'inspire',
    function () {
        $this->comment(
            Inspiring::quote()
        );
    }
)->purpose(
    'Display an inspiring quote'
);


/*
|--------------------------------------------------------------------------
| Follow Up Reminder Command
|--------------------------------------------------------------------------
*/

Artisan::command(
    'followups:check-due',
    function () {

        $processed =
            app(
                FollowUpReminderService::class
            )->processDue();

        $this->info(
            "{$processed} follow-up(s) processed."
        );

    }
)->purpose(
    'Check and process due CRM follow-ups'
);


/*
|--------------------------------------------------------------------------
| Scheduler
|--------------------------------------------------------------------------
*/

Schedule::command(
    'followups:check-due'
)
    ->everyMinute()
    ->withoutOverlapping(5);