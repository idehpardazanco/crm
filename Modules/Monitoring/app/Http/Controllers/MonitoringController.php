<?php

namespace Modules\Monitoring\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Monitoring\app\Models\ActivityLog;
use Modules\Monitoring\app\Models\SystemLog;
use Modules\Monitoring\app\Models\RequestLog;

class MonitoringController
{
    public function activities(Request $request)
    {
        $data = ActivityLog::query()
            ->when($request->module, fn($q) => $q->where('module', $request->module))
            ->when($request->search, fn($q) => $q->where('action', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return [
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'total' => $data->total(),
            ]
        ];
    }

    public function systemLogs(Request $request)
    {
        $data = SystemLog::query()
            ->when($request->level, fn($q) => $q->where('level', $request->level))
            ->when($request->search, fn($q) => $q->where('message', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return [
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'total' => $data->total(),
            ]
        ];
    }

    public function requestLogs(Request $request)
    {
        $data = RequestLog::query()
            ->when($request->status, fn($q) => $q->where('status_code', $request->status))
            ->when($request->search, fn($q) => $q->where('url', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return [
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'total' => $data->total(),
            ]
        ];
    }
}