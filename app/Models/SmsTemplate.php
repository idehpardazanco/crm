<?php

namespace App\Models;
use App\Enums\SmsTemplateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => SmsTemplateType::class,
    ];

    public function smsLogs(): HasMany
    {
        return $this->hasMany(SmsLog::class);
    }
}