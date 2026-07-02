<?php

namespace Modules\Monitoring\app\Services;

use Modules\Monitoring\Models\SystemLog;
use Modules\Monitoring\Models\ActivityLog;
use Modules\Monitoring\Models\RequestLog;
use Throwable;

class MonitoringService
{
    /**
     * ثبت رفتار کاربر
     */
    public function activity(string $action, string $module, array $meta = []): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action'  => $action,
            'module'  => $module,
            'meta'    => $meta,
        ]);
    }

    /**
     * ثبت لاگ سیستم
     */
    public function system(string $level, string $message, array $context = []): void
    {
        SystemLog::create([
            'level'   => $level,
            'message' => $message,
            'context' => $context,
        ]);
    }

    /**
     * ثبت خطاها
     */
    public function exception(Throwable $e): void
    {
        SystemLog::create([
            'level'   => 'error',
            'message' => $e->getMessage(),
            'context' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ],
        ]);
    }

    /**
     * ثبت request ها
     */
    public function request(array $data): void
    {
        RequestLog::create([
            'method'      => $data['method'],
            'url'         => $data['url'],
            'ip'          => $data['ip'] ?? null,
            'user_id'     => auth()->id(),
            'headers'     => $data['headers'] ?? [],
            'payload'     => $data['payload'] ?? [],
            'status_code' => $data['status_code'] ?? null,
            'duration'    => $data['duration'] ?? null,
        ]);
    }
}