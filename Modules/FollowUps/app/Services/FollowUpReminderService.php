<?php

namespace Modules\FollowUps\app\Services;

use Modules\FollowUps\app\Models\FollowUp;
use Modules\Monitoring\app\Services\MonitoringService;

class FollowUpReminderService
{
    public function __construct(
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function processDue(): int
    {
        $processed = 0;

        FollowUp::query()
            ->with([
                'contact:id,name,mobile,business_name',
            ])
            ->where(
                'status',
                'pending'
            )
            ->whereNull(
                'notified_at'
            )
            ->where(
                'follow_up_at',
                '<=',
                now()
            )
            ->orderBy('id')
            ->chunkById(
                100,
                function ($followUps) use (
                    &$processed
                ) {
                    foreach (
                        $followUps as $followUp
                    ) {
                        $notifiedAt =
                            now();

                        /*
                         * آپدیت اتمیک:
                         * حتی اگر دو Process همزمان اجرا شوند
                         * فقط یکی موفق می‌شود.
                         */
                        $updated =
                            FollowUp::query()
                                ->whereKey(
                                    $followUp->id
                                )
                                ->where(
                                    'status',
                                    'pending'
                                )
                                ->whereNull(
                                    'notified_at'
                                )
                                ->where(
                                    'follow_up_at',
                                    '<=',
                                    $notifiedAt
                                )
                                ->update([
                                    'notified_at' =>
                                        $notifiedAt,
                                ]);

                        if ($updated !== 1) {
                            continue;
                        }

                        $this
                            ->monitoringService
                            ->activity(
                                'follow_up_due',
                                'FollowUps',
                                [
                                    'follow_up_id' =>
                                        $followUp->id,

                                    'contact_id' =>
                                        $followUp
                                            ->contact_id,

                                    'contact_name' =>
                                        $followUp
                                            ->contact
                                            ?->name,

                                    'title' =>
                                        $followUp->title,

                                    'follow_up_at' =>
                                        $followUp
                                            ->follow_up_at
                                            ?->toDateTimeString(),
                                ],
                                $followUp->user_id
                            );

                        $processed++;
                    }
                }
            );

        return $processed;
    }
}