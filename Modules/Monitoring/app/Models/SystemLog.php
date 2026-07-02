<?php

namespace Modules\Monitoring\app\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $fillable = [
        'level',
        'message',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];
}