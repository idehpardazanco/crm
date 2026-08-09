<?php

namespace Modules\Sms\app\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Sms\app\Models\SmsLog;

class SmsLogService
{
    public function paginate(
        ?string $search,
        User $user
    ): LengthAwarePaginator {
        return SmsLog::query()
            ->with([
                'sendable',
                'user:id,name',
                'template:id,title',
            ])
            ->when(
                ! $user->hasRole(
                    'super_admin'
                ),
                fn ($query) =>
                    $query->where(
                        'user_id',
                        $user->id
                    )
            )
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