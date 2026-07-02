<?php

namespace Modules\Monitoring\app\Services;

use Modules\Monitoring\Models\SystemLog;
use Modules\Monitoring\Models\ActivityLog;

/**
 * Central monitoring service
 */
class MonitoringService
{
    public function logSystem(string $level, string $message, array $context = []): void
    {
        SystemLog::create([
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ]);
    }

    public function logActivity(string $action, string $module, array $meta = []): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'meta' => $meta,
        ]);
    }
}