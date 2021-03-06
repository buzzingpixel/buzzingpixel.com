<?php

declare(strict_types=1);

namespace App\Context\Users;

use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Services\SaveUser;
use App\Payload\Payload;

class UserApi
{
    public function __construct(
        private SaveUser $saveUser
    ) {
    }

    public function saveUser(UserEntity $user): Payload
    {
        return $this->saveUser->save($user);
    }
}
