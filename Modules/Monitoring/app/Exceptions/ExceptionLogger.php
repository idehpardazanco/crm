<?php

namespace Modules\Monitoring\Exceptions;

use Throwable;
use Modules\Monitoring\Models\SystemLog;

/**
 * Global exception logger
 */
class ExceptionLogger
{
    public static function log(Throwable $e): void
    {
        SystemLog::create([
            'level' => 'error',
            'message' => $e->getMessage(),
            'context' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ],
        ]);
    }
}