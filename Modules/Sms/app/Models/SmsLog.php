<?php

namespace Modules\Sms\app\Models;


use Illuminate\Database\Eloquent\Model;
use Modules\Contacts\app\Models\Contact;
use App\Models\User;



class SmsLog extends Model
{


    protected $fillable = [

        'user_id',

        'contact_id',

        'from',

        'to',

        'message',

        'status',

        'response',

    ];

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function contact()
    {
        return $this->belongsTo(
            Contact::class
        );
    }


}