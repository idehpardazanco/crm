<?php

namespace Modules\Sms\app\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Sms\app\Models\SmsLog;

class SmsLogService
{
    public function paginate(
        ?string $search = null
    ): LengthAwarePaginator {
        return SmsLog::query()
            ->with([
                'sendable',
                'user:id,name',
                'template:id,title',
            ])
            ->when(
                $search,
                function ($query) use ($search) {
                    $query->where(
                        function ($query) use ($search) {
                            $query
                                ->where(
                                    'mobile',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'message',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }
}