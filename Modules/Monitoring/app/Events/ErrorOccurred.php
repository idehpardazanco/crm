<?php

namespace Modules\Monitoring\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ErrorOccurred
{
    use Dispatchable, SerializesModels;

    public array $error;

    public function __construct(array $error)
    {
        $this->error = $error;
    }
}