<?php

namespace Modules\Monitoring\app\Services;

use Modules\Monitoring\Models\SystemLog;
use Modules\Monitoring\Models\ActivityLog;
use Modules\Monitoring\Models\RequestLog;
use Modules\Monitoring\Events\ErrorOccurred;
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
    public function exception($e)
    {
        $error = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'time' => now()->toDateTimeString(),
        ];

        // ذخیره در DB
        \Modules\Monitoring\APP\Models\SystemLog::create([
            'level' => 'error',
            'message' => $e->getMessage(),
        ]);

        // 🔥 ارسال real-time event
        event(new ErrorOccurred($error));
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