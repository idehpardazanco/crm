<?php

namespace Modules\Monitoring\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $fillable = [
        'level',
        'channel',
        'message',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];
}