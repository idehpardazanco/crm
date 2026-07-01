<?php

namespace Modules\Sms\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Sms\app\Enums\SmsStatus;

class SmsLog extends Model
{
    protected $fillable = [
        'sendable_type',
        'sendable_id',
        'user_id',
        'provider',
        'from_number',
        'mobile',
        'message',
        'status',
        'request_payload',
        'provider_response',
        'error_message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => SmsStatus::class,
            'request_payload' => 'array',
            'provider_response' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function sendable(): MorphTo
    {
        return $this->morphTo();
    }
}