<?php

namespace Modules\Sms\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Settings\app\Models\Setting;

class SmsSettingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render(
            'Sms/Settings',
            [
                'settings' => [
                    'sms_from' => Setting::query()
                        ->where(
                            'key',
                            'sms_from'
                        )
                        ->value('value'),

                    'sms_username' => Setting::query()
                        ->where(
                            'key',
                            'sms_username'
                        )
                        ->value('value'),

                    'sms_password' => '',
                ],
            ]
        );
    }

    public function update(
        Request $request
    ): RedirectResponse {
        $data = $request->validate([
            'sms_from' => [
                'required',
                'string',
                'max:30',
            ],

            'sms_username' => [
                'required',
                'string',
                'max:255',
            ],

            'sms_password' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        Setting::query()->updateOrCreate(
            [
                'key' => 'sms_from',
            ],
            [
                'value' => $data['sms_from'],
            ]
        );

        Setting::query()->updateOrCreate(
            [
                'key' => 'sms_username',
            ],
            [
                'value' => $data['sms_username'],
            ]
        );

        if (! empty($data['sms_password'])) {
            Setting::query()->updateOrCreate(
                [
                    'key' => 'sms_password',
                ],
                [
                    'value' => $data['sms_password'],
                ]
            );
        }

        return back()->with(
            'success',
            'تنظیمات پیامک ذخیره شد.'
        );
    }
}