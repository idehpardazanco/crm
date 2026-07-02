<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Central SMS Log Model
 */
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

    protected $casts = [
        'request_payload' => 'array',
        'provider_response' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Relation: who triggered SMS (polymorphic)
     */
    public function sendable()
    {
        return $this->morphTo();
    }

    /**
     * Relation: user who sent SMS
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}