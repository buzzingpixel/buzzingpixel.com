<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Config\General;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;
use function count;
use function is_array;

class FetchTotalLicenses
{
    public function __construct(
        private General $config,
        private LoggerInterface $logger,
        private EntityManager $entityManager,
    ) {
    }

    public function fetch(?LicenseQueryBuilder $queryBuilder = null): int
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

    private function innerFetch(?LicenseQueryBuilder $queryBuilder = null): int
    {
        $queryBuilder ??= new LicenseQueryBuilder();

        $queryBuilder = $queryBuilder->withLimit(null)
            ->withOffset(null);

        $result = $queryBuilder
            ->createQuery(entityManager: $this->entityManager)
            ->getResult(AbstractQuery::HYDRATE_SCALAR);

        assert(is_array($result));

        return count($result);
    }
}
