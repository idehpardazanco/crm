<?php

namespace Modules\Settings\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Settings\app\Models\Setting;

class SettingsController
{
    public function sms()
    {
        return [
            'sms_from' => Setting::where('key', 'sms_from')->value('value'),
            'sms_username' => Setting::where('key', 'sms_username')->value('value'),
            'sms_password' => Setting::where('key', 'sms_password')->value('value'),
            'sms_driver' => Setting::where('key', 'sms_driver')->value('value'),
        ];
    }

    public function updateSms(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return ['status' => 'ok'];
    }
}