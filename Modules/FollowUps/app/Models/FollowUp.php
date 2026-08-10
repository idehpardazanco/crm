<?php

namespace Modules\FollowUps\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Contacts\app\Models\Contact;

class FollowUp extends Model
{
    protected $fillable = [
        'contact_id',
        'user_id',
        'title',
        'description',
        'follow_up_at',
        'status',
        'notified_at',
    ];

    protected $casts = [
        'follow_up_at' =>
            'datetime',

        'notified_at' =>
            'datetime',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(
            Contact::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}