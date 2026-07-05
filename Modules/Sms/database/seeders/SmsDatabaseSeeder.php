<?php

namespace Modules\Settings\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\app\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'sms_from'], ['value' => '9982008568']);

        Setting::updateOrCreate(['key' => 'sms_username'], ['value' => 'zarinpayamak']);

        Setting::updateOrCreate(['key' => 'sms_password'], ['value' => 'esmaeil321']);

        Setting::updateOrCreate(['key' => 'sms_driver'], ['value' => 'payam_matni']);
    }
}