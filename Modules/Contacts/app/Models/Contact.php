<?php

namespace Modules\Contacts\app\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'address'
    ];
}