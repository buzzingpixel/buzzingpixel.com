<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserSession;
use App\Payload\Payload;

class CreateUserSession
{
    public function __construct(private SaveUserSession $saveUserSession)
    {
    }

    public function create(User $user): ?UserSession
    {
        $entity = new UserSession($user->id());

        $payload = $this->saveUserSession->save($entity);

        if ($payload->getStatus() === Payload::STATUS_ERROR) {
            return null;
        }

        return $entity;
    }
}
