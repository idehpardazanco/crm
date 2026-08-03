<?php

namespace Modules\Contacts\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Interactions\app\Models\Interaction;
use App\Models\User;


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
            \Modules\Interactions\app\Models\Interaction::class
        );
    }

}