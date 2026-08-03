<?php

namespace Modules\FollowUps\app\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
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
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}