<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\User;
use App\Payload\Payload;
use buzzingpixel\cookieapi\CookieApi;
use DateTimeImmutable;
use DateTimeZone;

use function strtotime;

class LogInAsUser
{
    public function __construct(
        private CookieApi $cookieApi,
        private CreateUserSession $createUserSession,
    ) {
    }

    public function logInAsUser(User $user): Payload
    {
        $session = $this->createUserSession->create($user);

        if ($session === null) {
            return new Payload(Payload::STATUS_ERROR);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
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
                'message' => 'Logged in as ' . $user->emailAddress(),
                'id' => $session->id(),
                'userEntity' => $user,
            ],
        );
    }
}
