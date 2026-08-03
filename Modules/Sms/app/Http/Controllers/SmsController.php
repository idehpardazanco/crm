<?php

namespace Modules\Sms\app\Http\Controllers;


use Illuminate\Routing\Controller;

use Modules\Sms\app\Http\Requests\SendSmsRequest;
use Modules\Sms\app\Services\SmsService;



class SmsController extends Controller
{


    public function __construct(

        protected SmsService $service

    )
    {

    }





    public function send(
        SendSmsRequest $request
    )
    {


        return $this->service->send(

            $request->to,

            $request->message,

            $request->contact_id

        );


    }


}