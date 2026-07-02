<?php

namespace Modules\Monitoring\app\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}