<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\User;
use App\Persistence\Entities\Users\UserPasswordResetTokenRecord;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;
use Throwable;

class FetchUserByResetToken
{
    public function __construct(
        private EntityManager $entityManager,
        private FetchOneUser $fetchOneUser,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function fetch(string $token): ?User
    {
        try {
            return $this->innerFetch($token);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for user by reset token',
                ['exception' => $exception],
            );

            return null;
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    private function innerFetch(string $token): ?User
    {
        try {
            $userId = (string) $this->entityManager
                ->getRepository(UserPasswordResetTokenRecord::class)
                ->createQueryBuilder('t')
                ->select('t.userId')
                ->where('t.id = :id')
                ->setParameter('id', $token)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $exception) {
            return null;
        }

        return $this->fetchOneUser->fetch(
            (new UserQueryBuilder())->withId($userId)
        );
    }
}
