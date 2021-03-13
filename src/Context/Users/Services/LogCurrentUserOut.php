<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Payload\Payload;
use App\Persistence\Entities\Users\UserSessionRecord;
use buzzingpixel\cookieapi\interfaces\CookieApiInterface;
use Doctrine\ORM\EntityManager;

class LogCurrentUserOut
{
    public function __construct(
        private CookieApiInterface $cookieApi,
        private EntityManager $entityManager
    ) {
    }

    public function logOut(): Payload
    {
        $cookie = $this->cookieApi->retrieveCookie('user_session_token');

        if ($cookie === null) {
            return new Payload(
                Payload::STATUS_NOT_VALID,
                ['message' => 'User is not logged in']
            );
        }

        $sessionId = $cookie->value();

        $this->cookieApi->deleteCookie($cookie);

        if ($sessionId === '') {
            return new Payload(Payload::STATUS_SUCCESSFUL);
        }

        $this->entityManager->createQueryBuilder()
            ->delete(UserSessionRecord::class, 's')
            ->where('s.id = :id')
            ->setParameter('id', $sessionId)
            ->getQuery()
            ->execute();

        return new Payload(Payload::STATUS_SUCCESSFUL);
    }
}
