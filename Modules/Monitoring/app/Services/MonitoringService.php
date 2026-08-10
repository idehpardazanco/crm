<?php

namespace Modules\Monitoring\app\Services;

use Modules\Monitoring\app\Models\ActivityLog;
use Modules\Monitoring\app\Models\RequestLog;
use Modules\Monitoring\app\Models\SystemLog;
use Throwable;

class MonitoringService
{
    public function activity(
        string $action,
        string $module,
        array $meta = [],
        ?int $userId = null
    ): ActivityLog {
        return ActivityLog::query()->create([
            'user_id' =>
                $userId
                ?? auth()->id()
                ?? ($meta['user_id'] ?? null),

            'action' => $action,

            'module' => $module,

            'meta' => $meta ?: null,
        ]);
    }

    public function error(
        Throwable $exception,
        ?string $context = null
    ): SystemLog {
        return SystemLog::query()->create([
            'level' => 'error',

            'message' =>
                $exception->getMessage(),

            'context' => [
                'context' => $context,
                'exception' => $exception::class,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ],
        ]);
    }

    public function info(
        string $message,
        array $context = []
    ): SystemLog {
        return SystemLog::query()->create([
            'level' => 'info',

            'message' => $message,

            'context' =>
                $context ?: null,
        ]);
    }

    public function request(
        array $data
    ): RequestLog {
        return RequestLog::query()
            ->create($data);
    }

    public function exception(
        Throwable $exception
    ): void {
        logger()->error(
            $exception->getMessage(),
            [
                'exception' =>
                    $exception::class,

                'file' =>
                    $exception->getFile(),

                'line' =>
                    $exception->getLine(),
            ]
        );
    }
}