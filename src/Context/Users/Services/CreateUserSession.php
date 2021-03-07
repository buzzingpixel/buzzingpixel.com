<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Entities\UserSessionEntity;
use App\Payload\Payload;
use DateTimeZone;
use Safe\DateTimeImmutable;

class CreateUserSession
{
    public function __construct(private SaveUserSession $saveUserSession)
    {
    }

    public function create(UserEntity $user): Payload
    {
        $currentDate = new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC')
        );

        return $this->saveUserSession->save(
            new UserSessionEntity($user->id())
        );
    }
}
