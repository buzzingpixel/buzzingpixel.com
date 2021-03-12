<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserPasswordResetToken;
use App\Payload\Payload;

class GeneratePasswordResetToken
{
    public function __construct(
        private SaveUserPasswordResetToken $saveUserPasswordResetToken
    ) {
    }

    public function generate(User $user): ?UserPasswordResetToken
    {
        $tokenEntity = new UserPasswordResetToken(
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
