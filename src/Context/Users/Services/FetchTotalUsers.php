<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Persistence\Entities\Users\UserRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;
use Throwable;

class FetchTotalUsers
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function fetch(): int
    {
        try {
            return $this->innerFetch();
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for total users',
                ['exception' => $exception],
            );

            return 0;
        }
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function innerFetch(): int
    {
        return (int) $this->entityManager
            ->getRepository(UserRecord::class)
            ->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
