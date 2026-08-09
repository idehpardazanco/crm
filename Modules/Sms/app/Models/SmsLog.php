<?php

namespace Modules\Sms\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Sms\app\Enums\SmsStatus;

class SmsLog extends Model
{
    protected $fillable = [
        'sendable_type',
        'sendable_id',
        'user_id',
        'sms_template_id',
        'provider',
        'from_number',
        'mobile',
        'message',
        'status',
        'request_payload',
        'provider_response',
        'error_message',
        'response_code',
        'cost',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => SmsStatus::class,
            'request_payload' => 'array',
            'provider_response' => 'array',
            'sent_at' => 'datetime',
            'cost' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(
            SmsTemplate::class,
            'sms_template_id'
        );
    }

    public function sendable(): MorphTo
    {
        return $this->morphTo();
    }
}