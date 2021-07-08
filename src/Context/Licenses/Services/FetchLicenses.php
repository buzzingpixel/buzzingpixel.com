<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Entities\LicenseCollection;
use App\Persistence\Entities\Licenses\LicenseRecord;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class FetchLicenses
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function fetch(
        LicenseQueryBuilder $queryBuilder,
    ): LicenseCollection {
        try {
            return $this->innerFetch($queryBuilder);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                /** @noinspection PhpUnhandledExceptionInspection */
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for Licenses',
                ['exception' => $exception],
            );

            return new LicenseCollection();
        }
    }

    /** @phpstan-ignore-next-line */
    private function innerFetch(
        LicenseQueryBuilder $queryBuilder,
    ): LicenseCollection {
        /** @psalm-suppress MixedArgument */
        return new LicenseCollection(array_map(
            static fn (LicenseRecord $r) => License::fromRecord(
                $r
            ),
            $queryBuilder->createQuery(
                $this->entityManager
            )->getResult(),
        ));
    }
}
