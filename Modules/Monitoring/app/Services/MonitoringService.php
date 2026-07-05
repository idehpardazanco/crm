<?php

namespace Modules\Monitoring\app\Services;

use Modules\Monitoring\app\Models\ActivityLog;
use Modules\Monitoring\app\Models\SystemLog;
use Throwable;

class MonitoringService
{
    /**
     * Store general activity log
     */
    public function activity(string $action, string $module, array $meta = []): ActivityLog
    {
        return ActivityLog::create([
            'action' => $action,
            'module' => $module,
            'meta'   => json_encode($meta),
        ]);
    }

    /**
     * Store system error log
     */
    public function error(Throwable $exception, string $context = null): SystemLog
    {
        return SystemLog::create([
            'level'   => 'error',
            'message' => $exception->getMessage(),
            'context' => $context,
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
        ]);
    }

    /**
     * Store info log
     */
    public function info(string $message, array $context = []): SystemLog
    {
        return SystemLog::create([
            'level'   => 'info',
            'message' => $message,
            'context' => json_encode($context),
        ]);
    }

    public function exception($e)
    {
        // log or send to monitoring system
        logger()->error($e);
    }
}