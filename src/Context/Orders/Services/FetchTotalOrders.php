<?php

declare(strict_types=1);

namespace App\Context\Orders\Services;

use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use Config\General;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;
use function count;
use function is_array;

class FetchTotalOrders
{
    public function __construct(
        private General $config,
        private LoggerInterface $logger,
        private EntityManager $entityManager,
    ) {
    }

    public function fetch(?OrderQueryBuilder $queryBuilder = null): int
    {
        try {
            return $this->innerFetch($queryBuilder);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                /** @noinspection PhpUnhandledExceptionInspection */
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for total orders',
                ['exception' => $exception],
            );

            return 0;
        }
    }

    private function innerFetch(?OrderQueryBuilder $queryBuilder = null): int
    {
        $queryBuilder ??= new OrderQueryBuilder();

        $queryBuilder = $queryBuilder->withLimit(null)
            ->withOffset(null);

        $result = $queryBuilder->createQuery($this->entityManager)
            ->getResult(AbstractQuery::HYDRATE_SCALAR);

        assert(is_array($result));

        return count($result);
    }
}
