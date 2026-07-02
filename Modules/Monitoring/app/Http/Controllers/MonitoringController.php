<?php

namespace Modules\Monitoring\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Monitoring\Models\ActivityLog;
use Modules\Monitoring\Models\SystemLog;
use Modules\Monitoring\Models\RequestLog;

class MonitoringController
{
    /**
     * Activity Logs
     */
    public function activities(Request $request)
    {
        $data = ActivityLog::query()
            ->when($request->module, fn($q) => $q->where('module', $request->module))
            ->latest()
            ->paginate(15);

        return response()->json($data);
    }

    /**
     * System Logs
     */
    public function systemLogs(Request $request)
    {
        $data = SystemLog::query()
            ->when($request->level, fn($q) => $q->where('level', $request->level))
            ->latest()
            ->paginate(15);

        return response()->json($data);
    }

    /**
     * Request Logs
     */
    public function requestLogs()
    {
        return response()->json(
            RequestLog::latest()->paginate(15)
        );
    }
}