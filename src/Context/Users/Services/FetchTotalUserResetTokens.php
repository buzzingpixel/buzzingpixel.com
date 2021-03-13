<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\User;
use App\Persistence\Entities\Users\UserPasswordResetTokenRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;
use Throwable;

class FetchTotalUserResetTokens
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function fetch(User $user): int
    {
        try {
            return $this->innerFetch($user);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for user reset tokens count',
                ['exception' => $exception],
            );

            return 0;
        }
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function innerFetch(User $user): int
    {
        return (int) $this->entityManager
            ->getRepository(UserPasswordResetTokenRecord::class)
            ->createQueryBuilder('t')
            ->select('count(t.id)')
            ->where('t.userId = :userId')
            ->setParameter('userId', $user->id())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
