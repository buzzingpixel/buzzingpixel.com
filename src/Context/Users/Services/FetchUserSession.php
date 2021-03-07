<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserSessionEntity;
use App\Persistence\QueryBuilders\Users\UserSessionQueryBuilder;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Log\LoggerInterface;
use Throwable;

class FetchUserSession
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function fetch(UserSessionQueryBuilder $queryBuilder): ?UserSessionEntity
    {
        try {
            return $this->innerFetch($queryBuilder);
        } catch (Throwable $e) {
            if ($this->config->devMode()) {
                throw $e;
            }

            $this->logger->emergency(
                'An exception was caught querying for a user session',
                ['exception' => $e],
            );

            return null;
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    private function innerFetch(UserSessionQueryBuilder $queryBuilder): ?UserSessionEntity
    {
        $record = $queryBuilder->withLimit(1)
            ->createQuery($this->entityManager)
            ->getOneOrNullResult();

        if (! $record) {
            return null;
        }

        return UserSessionEntity::fromRecord($record);
    }
}
