<?php

namespace Modules\Orders\app\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'contact_id',
        'title',
        'amount',
        'status',
        'description'
    ];

    public function contact()
    {
        return $this->belongsTo(\Modules\Contacts\app\Models\Contact::class);
    }
}