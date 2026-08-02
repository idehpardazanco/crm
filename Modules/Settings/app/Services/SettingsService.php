<?php

namespace Modules\Settings\app\Services;

use Modules\Settings\app\Models\Setting;

class SettingsService
{
    public function get(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public function set(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}