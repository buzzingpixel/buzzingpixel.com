<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\Entities\SoftwareVersionCollection;
use App\Persistence\Entities\Software\SoftwareVersionRecord;
use App\Persistence\QueryBuilders\Software\SoftwareVersionQueryBuilder;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class FetchSoftwareVersions
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function fetch(
        SoftwareVersionQueryBuilder $queryBuilder,
    ): SoftwareVersionCollection {
        try {
            return $this->innerFetch($queryBuilder);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for software vrsion',
                ['exception' => $exception],
            );

            return new SoftwareVersionCollection();
        }
    }

    /** @phpstan-ignore-next-line */
    private function innerFetch(
        SoftwareVersionQueryBuilder $queryBuilder,
    ): SoftwareVersionCollection {
        /** @psalm-suppress MixedArgument */
        return new SoftwareVersionCollection(array_map(
            static fn (
                SoftwareVersionRecord $r
            ) => SoftwareVersion::fromRecord($r),
            $queryBuilder->createQuery(
                $this->entityManager,
            )->getResult()
        ));
    }
}
