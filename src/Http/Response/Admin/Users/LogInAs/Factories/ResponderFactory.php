<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\LogInAs\Factories;

use App\Http\Response\Admin\Users\LogInAs\Contracts\ResponderContract;
use App\Http\Response\Admin\Users\LogInAs\Services\ResponderError;
use App\Http\Response\Admin\Users\LogInAs\Services\ResponderSuccess;
use App\Payload\Payload;

class ResponderFactory
{
    public function __construct(
        private ResponderError $error,
        private ResponderSuccess $success,
    ) {
    }

    public function make(Payload $payload): ResponderContract
    {
        if ($payload->getStatus() === Payload::STATUS_SUCCESSFUL) {
            return $this->success;
        }

        return $this->error;
    }
}
