<?php

namespace Modules\Settings\app\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Key-Value system settings
 */
class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type'
    ];
}