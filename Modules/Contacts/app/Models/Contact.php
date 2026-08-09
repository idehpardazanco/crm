<?php

namespace Modules\Contacts\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\FollowUps\app\Models\FollowUp;
use Modules\Interactions\app\Models\Interaction;

class Contact extends Model
{
    protected $fillable = [
        'business_name',
        'name',
        'mobile',
        'phone',
        'email',
        'city',
        'category',
        'source',
        'status',
        'assigned_user_id',
        'address',
        'description',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_user_id'
        );
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(
            Interaction::class
        );
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(
            FollowUp::class
        );
    }
}