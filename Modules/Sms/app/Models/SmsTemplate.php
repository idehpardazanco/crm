<?php

namespace Modules\Sms\app\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = [
        'title',
        'body',
        'type',
        'status',
    ];
}