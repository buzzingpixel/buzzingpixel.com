<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;
use function count;
use function is_array;

class FetchTotalUsers
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function fetch(?UserQueryBuilder $queryBuilder = null): int
    {
        try {
            return $this->innerFetch($queryBuilder);
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

    private function innerFetch(?UserQueryBuilder $queryBuilder = null): int
    {
        if ($queryBuilder === null) {
            $queryBuilder = new UserQueryBuilder();
        }

        $queryBuilder = $queryBuilder->withLimit(null)
            ->withOffset(null);

        $result = $queryBuilder->createQuery($this->entityManager)
            ->getResult(AbstractQuery::HYDRATE_SCALAR);

        assert(is_array($result));

        return count($result);
    }
}
