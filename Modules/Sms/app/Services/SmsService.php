<?php

namespace Modules\Sms\app\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Sms\app\Enums\SmsStatus;
use Modules\Sms\app\Jobs\SendSmsJob;
use Modules\Sms\app\Models\SmsLog;

class SmsService
{
    public function send(
        string $mobile,
        string $message,
        ?Model $sendable = null,
        ?int $userId = null,
        bool $queue = true
    ): SmsLog {
        $smsLog = SmsLog::query()->create([
            'sendable_type' => $sendable?->getMorphClass(),
            'sendable_id' => $sendable?->getKey(),
            'user_id' => $userId,
            'provider' => config('sms.default'),
            'from_number' => config('sms.providers.payam_matni.from'),
            'mobile' => $mobile,
            'message' => $message,
            'status' => SmsStatus::QUEUED,
        ]);

        if ($queue) {
            SendSmsJob::dispatch($smsLog->id);
        } else {
            SendSmsJob::dispatchSync($smsLog->id);
        }

        return $smsLog;
    }

    public function sendOtp(string $mobile, string $code, ?int $userId = null): SmsLog
    {
        $message = "کد ورود شما: {$code}\nاین کد تا ۲ دقیقه معتبر است.";

        return $this->send(
            mobile: $mobile,
            message: $message,
            userId: $userId,
            queue: true
        );
    }
}