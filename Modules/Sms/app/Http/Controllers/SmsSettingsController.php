<?php

namespace Modules\Sms\app\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controller;
use Modules\Settings\app\Models\Setting;

class SmsSettingsController extends Controller
{
    public function index()
    {

        return Inertia::render(
            'Sms/Settings',
            [

                'settings'=>[

                    'sms_from'=>Setting::where(
                        'key',
                        'sms_from'
                    )->value('value'),


                    'sms_username'=>Setting::where(
                        'key',
                        'sms_username'
                    )->value('value'),


                    'sms_password'=>Setting::where(
                        'key',
                        'sms_password'
                    )->value('value'),

                ]

            ]
        );

    }

    public function update(Request $request)
    {

        $data = $request->validate([

            'sms_from'=>'required|string',

            'sms_username'=>'required|string',

            'sms_password'=>'required|string',

        ]);

        foreach($data as $key=>$value){

            Setting::updateOrCreate(

                [
                    'key'=>$key
                ],

                [
                    'value'=>$value
                ]

            );

        }

        return back();

    }

}