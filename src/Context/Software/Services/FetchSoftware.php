<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareCollection;
use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class FetchSoftware
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function fetch(
        SoftwareQueryBuilder $queryBuilder
    ): SoftwareCollection {
        try {
            return $this->innerFetch($queryBuilder);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for software',
                ['exception' => $exception],
            );

            return new SoftwareCollection();
        }
    }

    /** @phpstan-ignore-next-line */
    private function innerFetch(
        SoftwareQueryBuilder $queryBuilder
    ): SoftwareCollection {
        /** @psalm-suppress MixedArgument */
        return new SoftwareCollection(array_map(
            static fn (SoftwareRecord $r) => Software::fromRecord(
                $r
            ),
            $queryBuilder->createQuery(
                $this->entityManager,
            )->getResult(),
        ));
    }
}
