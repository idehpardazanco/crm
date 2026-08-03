<?php

namespace Modules\Sms\app\Services;

use Modules\Sms\app\Models\SmsLog;

class SmsLogService
{
    public function paginate(?string $search = null)
    {
        return SmsLog::query()
            ->with([
                'contact',
                'user'
            ])
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('to', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");

                });

            })
            ->latest()
            ->paginate(20);
    }
}