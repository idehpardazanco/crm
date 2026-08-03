<?php

namespace Modules\Interactions\app\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Modules\Contacts\app\Models\Contact;



class Interaction extends Model
{
    protected $fillable = [

        'contact_id',

        'user_id',

        'type',

        'subject',

        'description',

        'result',

        'next_follow_up',

    ];

    protected $casts = [

        'next_follow_up'=>'datetime'

    ];

    public function contact()
    {

        return $this->belongsTo(
            Contact::class
        );

    }

    public function user()
    {

        return $this->belongsTo(
            User::class
        );

    }

}