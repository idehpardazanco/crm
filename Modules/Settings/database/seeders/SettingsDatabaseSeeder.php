<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Models\Setting;

/**
 * Default system settings
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'sms.from'],
            ['value' => '9982008568', 'type' => 'string']
        );

        Setting::updateOrCreate(
            ['key' => 'sms.username'],
            ['value' => 'zarinpayamak', 'type' => 'string']
        );

        Setting::updateOrCreate(
            ['key' => 'sms.password'],
            ['value' => 'esmaeil321', 'type' => 'string']
        );
    }
}