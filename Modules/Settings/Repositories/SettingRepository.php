<?php

namespace Modules\Settings\app\Repositories;

use Modules\Settings\Models\Setting;

/**
 * Data access layer for settings
 */
class SettingRepository
{
    public function get(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public function set(string $key, $value, string $type): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type'  => $type
            ]
        );
    }

    public function all(): array
    {
        return Setting::all()->pluck('value', 'key')->toArray();
    }
}