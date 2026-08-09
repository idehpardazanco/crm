<?php

namespace Modules\Orders\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Contacts\app\Models\Contact;
use Modules\Orders\app\Enums\OrderStatus;

class Order extends Model
{
    protected $fillable = [
        'contact_id',
        'user_id',
        'product_name',
        'amount',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => OrderStatus::class,
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(
            Contact::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}