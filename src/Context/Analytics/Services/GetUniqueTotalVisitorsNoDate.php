<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Contracts\GetUniqueTotalVisitorsContract;
use App\Persistence\Entities\Analytics\AnalyticsRecord;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;

class GetUniqueTotalVisitorsNoDate implements GetUniqueTotalVisitorsContract
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
            ->select('count(DISTINCT a.cookieId)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
