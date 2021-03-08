<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Entities\UserSessionEntity;
use App\Payload\Payload;

class CreateUserSession
{
    public function __construct(private SaveUserSession $saveUserSession)
    {
    }

    public function create(UserEntity $user): ?UserSessionEntity
    {
        $entity = new UserSessionEntity($user->id());

        $payload = $this->saveUserSession->save($entity);

        if ($payload->getStatus() === Payload::STATUS_ERROR) {
            return null;
        }

        return $entity;
    }
}
