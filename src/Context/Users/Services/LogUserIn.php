<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Entities\UserSessionEntity;
use App\Payload\Payload;
use buzzingpixel\cookieapi\CookieApi;
use DateTimeImmutable;
use DateTimeZone;

use function assert;
use function strtotime;

class LogUserIn
{
    public function __construct(
        private ValidateUserPassword $validateUserPassword,
        private CreateUserSession $createUserSession,
        private CookieApi $cookieApi,
    ) {
    }

    public function logUserIn(UserEntity $user, string $password): Payload
    {
        $user = $this->validateUserPassword->validate(
            $user,
            $password,
        );

        if ($user === null) {
            return new Payload(
                Payload::STATUS_NOT_VALID,
                ['message' => 'Your password is invalid'],
            );
        }

        $createSessionPayload = $this->createUserSession->create($user);

        if ($createSessionPayload->getStatus() !== Payload::STATUS_CREATED) {
            return new Payload(Payload::STATUS_ERROR);
        }

        /** @var array<string, string|UserSessionEntity> $result */
        $result = $createSessionPayload->getResult();

        $session = $result['userSessionEntity'];

        assert($session instanceof UserSessionEntity);

        $currentDate = new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC')
        );

        $currentDatePlus20Years = $currentDate->setTimestamp(
            strtotime('+ 20 years')
        );

        $this->cookieApi->saveCookie(
            $this->cookieApi->makeCookie(
                'user_session_token',
                $session->id(),
                $currentDatePlus20Years
            )
        );

        return new Payload(
            Payload::STATUS_SUCCESSFUL,
            [
                'id' => $session->id(),
                'userEntity' => $user,
            ],
        );
    }
}
