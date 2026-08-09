<?php

namespace Modules\Interactions\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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
        'status_after_call',
        'next_follow_up',
    ];

    protected $casts = [
        'next_follow_up' => 'datetime',
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