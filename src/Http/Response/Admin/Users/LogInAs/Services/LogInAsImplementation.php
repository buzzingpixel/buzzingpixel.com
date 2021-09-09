<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\LogInAs\Services;

use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Http\Response\Admin\Users\LogInAs\Contracts\LogInAsContract;
use App\Payload\Payload;

use function assert;

class LogInAsImplementation implements LogInAsContract
{
    public function __construct(private UserApi $userApi)
    {
    }

    public function logInAs(?User $user): Payload
    {
        assert($user instanceof User);

        return $this->userApi->logInAsUser($user);
    }
}
