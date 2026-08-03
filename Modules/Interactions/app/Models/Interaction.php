<?php

namespace Modules\Interactions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Contacts\Models\Contact;
use Modules\Users\Models\User;

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'user_id',
        'type',
        'subject',
        'description',
        'interaction_at',
        'follow_up_at',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'interaction_at' => 'datetime',
            'follow_up_at'   => 'datetime',
            'metadata'       => 'array',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}