<?php

namespace App\Models;
use App\Enums\ReminderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'title',
        'description',
        'remind_at',
        'status',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'status' => ReminderStatus::class,
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