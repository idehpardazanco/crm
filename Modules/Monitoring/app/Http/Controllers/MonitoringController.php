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
        return ActivityLog::query()
            ->when($request->module, function ($q) {
                $q->where('module', request('module'));
            })
            ->when($request->search, function ($q) {
                $q->where('action', 'like', '%'.request('search').'%');
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

    /**
     * System Logs (filter + search)
     */
    public function systemLogs(Request $request)
    {
        return SystemLog::query()
            ->when($request->level, function ($q) {
                $q->where('level', request('level'));
            })
            ->when($request->search, function ($q) {
                $q->where('message', 'like', '%'.request('search').'%');
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
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}