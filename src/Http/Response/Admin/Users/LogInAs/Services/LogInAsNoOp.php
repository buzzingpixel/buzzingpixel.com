<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\LogInAs\Services;

use App\Context\Users\Entities\User;
use App\Http\Response\Admin\Users\LogInAs\Contracts\LogInAsContract;
use App\Payload\Payload;

class LogInAsNoOp implements LogInAsContract
{
    public function logInAs(?User $user): Payload
    {
        return new Payload(
            Payload::STATUS_ERROR,
            ['message' => 'There was an error logging you in as user']
        );
    }
}
