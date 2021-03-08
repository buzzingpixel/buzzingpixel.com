<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Entities\UserPasswordResetTokenEntity;
use App\Payload\Payload;

class GeneratePasswordResetToken
{
    public function __construct(
        private SaveUserPasswordResetToken $saveUserPasswordResetToken
    ) {
    }

    public function generate(UserEntity $user): ?UserPasswordResetTokenEntity
    {
        $tokenEntity = new UserPasswordResetTokenEntity(
            $user->id(),
        );

        $savePayload = $this->saveUserPasswordResetToken->save(
            $tokenEntity,
        );

        if ($savePayload->getStatus() === Payload::STATUS_ERROR) {
            return null;
        }

        return $tokenEntity;
    }
}
