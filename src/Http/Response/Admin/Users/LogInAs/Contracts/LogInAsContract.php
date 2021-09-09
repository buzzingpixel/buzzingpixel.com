<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\LogInAs\Contracts;

use App\Context\Users\Entities\User;
use App\Payload\Payload;

interface LogInAsContract
{
    public function logInAs(?User $user): Payload;
}
