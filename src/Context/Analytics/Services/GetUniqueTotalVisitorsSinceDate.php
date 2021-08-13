<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Contracts\GetUniqueTotalVisitorsContract;
use App\Persistence\Entities\Analytics\AnalyticsRecord;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;

use function assert;

class GetUniqueTotalVisitorsSinceDate implements GetUniqueTotalVisitorsContract
{
    public function __construct(
        private EntityManager $entityManager,
    ) {
    }

    public function get(?DateTimeImmutable $date = null): int
    {
        assert($date instanceof DateTimeImmutable);

        /** @noinspection PhpUnhandledExceptionInspection */
        return (int) $this->entityManager
            ->getRepository(AnalyticsRecord::class)
            ->createQueryBuilder('a')
            ->select('count(DISTINCT a.cookieId)')
            ->where('a.date >= :date')
            ->setParameter('date', $date->format(
                DateTimeInterface::ATOM,
            ))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
