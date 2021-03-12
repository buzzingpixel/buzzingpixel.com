<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\User;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserSessionQueryBuilder;
use buzzingpixel\cookieapi\interfaces\CookieApiInterface;
use DateTimeZone;
use Safe\DateTimeImmutable;

use function time;

class FetchLoggedInUser
{
    public function __construct(
        private CookieApiInterface $cookieApi,
        private FetchUserSession $fetchUserSession,
        private SaveUserSession $saveUserSession,
        private FetchOneUser $fetchOneUser,
    ) {
    }

    public function fetch(): ?User
    {
        $cookie = $this->cookieApi->retrieveCookie('user_session_token');

        if ($cookie === null) {
            return null;
        }

        $session = $this->fetchUserSession->fetch(
            (new UserSessionQueryBuilder())
                ->withId($cookie->value())
        );

        if ($session === null) {
            $this->cookieApi->deleteCookie($cookie);

            return null;
        }

        /**
         * We don't want to touch the session (write to the database) every time
         * we fetch the current user. So we'll only do it once every 24 hours
         */
        $h24 = 86400;

        $diff = time() - $session->lastTouchedAt()->getTimestamp();

        if ($diff > $h24) {
            $this->saveUserSession->save($session->withLastTouchedAt(
                new DateTimeImmutable(
                    'now',
                    new DateTimeZone('UTC')
                )
            ));
        }

        return $this->fetchOneUser->fetch(
            (new UserQueryBuilder())
                ->withId($session->userId())
        );
    }
}
