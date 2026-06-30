<?php

namespace App\Models;
use App\Enums\CallResult;
use App\Enums\BusinessStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallLog extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'result',
        'description',
        'next_follow_up_at',
        'status_after_call',
    ];

    protected $casts = [
        'next_follow_up_at' => 'datetime',
        'result' => CallResult::class,
        'status_after_call' => BusinessStatus::class,
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}