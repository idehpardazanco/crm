<?php

namespace Modules\Sms\app\Services;


use Modules\Sms\app\Models\SmsLog;
use Modules\Sms\Contracts\SmsProviderInterface;
use Modules\Interactions\app\Models\Interaction;



class SmsService
{


    public function __construct(
        protected SmsProviderInterface $provider
    )
    {

    }




    public function send(string $to,string $message,?int $contactId = null): SmsLog
    {

        $response = $this->provider->send($to,$message);

        $log = SmsLog::create([
            'user_id' => auth()->id(),
            'contact_id' => $contactId,
            'from' => config('sms.from'),
            'to' => $to,
            'message' => $message,
            'status' => $response['status'] ?? 'failed',
            'response' => json_encode($response),
        ]);

        if($contactId)
        {
            Interaction::create([

                'contact_id' => $contactId,
                'user_id' => auth()->id(),
                'type' => 'sms',
                'subject' => 'ارسال پیامک',
                'description' => $message,
                'result' => $log->status,
            ]);
        }

        return $log;
    }

}