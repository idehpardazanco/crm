<?php

namespace Modules\Contacts\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FollowUps\app\Models\FollowUp;
use Modules\Interactions\app\Models\Interaction;
use Modules\Orders\app\Models\Order;

class Contact extends Model
{
    use SoftDeletes;

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

    public function assignedUser()
    {
        return $this->belongsTo(
            User::class,
            'assigned_user_id'
        );
    }

    public function interactions()
    {
        return $this->hasMany(
            Interaction::class
        );
    }

    public function followUps()
    {
        return $this->hasMany(
            FollowUp::class
        );
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class
        );
    }
}