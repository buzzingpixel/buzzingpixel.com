<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Contracts\GetTotalPageViewsContract;
use App\Persistence\Entities\Analytics\AnalyticsRecord;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;

class GetTotalPageViewsNoDate implements GetTotalPageViewsContract
{
    public function __construct(
        private EntityManager $entityManager,
    ) {
    }

    public function get(?DateTimeImmutable $date = null): int
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return (int) $this->entityManager
            ->getRepository(AnalyticsRecord::class)
            ->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
