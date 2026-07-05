<?php

namespace Modules\Monitoring\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Monitoring\Models\ActivityLog;
use Modules\Monitoring\Models\SystemLog;
use Modules\Monitoring\Models\RequestLog;

class MonitoringController
{
    /**
     * Activity Logs (filter + search)
     */
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
    /**
     * System Logs (filter + search)
     */
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

    /**
     * Request Logs (filter + search)
     */
    public function requestLogs(Request $request)
    {
        return RequestLog::query()
            ->when($request->status, function ($q) {
                $q->where('status_code', request('status'));
            })
            ->when($request->search, function ($q) {
                $q->where('url', 'like', '%'.request('search').'%');
            })
            ->when($request->from, function ($q) {
                $q->whereDate('created_at', '>=', request('from'));
            })
            ->when($request->to, function ($q) {
                $q->whereDate('created_at', '<=', request('to'));
            })
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}