<?php

namespace Modules\Monitoring\app\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = [
        'method',
        'url',
        'ip',
        'user_id',
        'headers',
        'payload',
        'status_code',
        'duration',
    ];

    protected $casts = [
        'headers' => 'array',
        'payload' => 'array',
    ];
}