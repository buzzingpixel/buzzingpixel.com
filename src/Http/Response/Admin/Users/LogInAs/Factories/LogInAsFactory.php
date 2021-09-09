<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\LogInAs\Factories;

use App\Context\Users\Entities\User;
use App\Http\Response\Admin\Users\LogInAs\Contracts\LogInAsContract;
use App\Http\Response\Admin\Users\LogInAs\Services\LogInAsImplementation;
use App\Http\Response\Admin\Users\LogInAs\Services\LogInAsNoOp;

class LogInAsFactory
{
    public function __construct(
        private LogInAsImplementation $logInAs,
        private LogInAsNoOp $logInAsNoOp,
    ) {
    }

    public function make(?User $user): LogInAsContract
    {
        if ($user === null) {
            return $this->logInAsNoOp;
        }

        return $this->logInAs;
    }
}
