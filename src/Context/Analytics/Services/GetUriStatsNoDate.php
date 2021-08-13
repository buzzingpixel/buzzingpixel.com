<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Contracts\GetUriStatsContract;
use App\Context\Analytics\Entities\UriStatsCollection;
use App\Context\Analytics\Factories\CreateUriStatusCollectionFromRecords;
use App\Persistence\Entities\Analytics\AnalyticsRecord;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;

use function assert;
use function is_array;

class GetUriStatsNoDate implements GetUriStatsContract
{
    public function __construct(
        private EntityManager $entityManager,
        private CreateUriStatusCollectionFromRecords $collectionFromRecords,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function get(?DateTimeImmutable $date = null): UriStatsCollection
    {
        $records = $this->entityManager
            ->getRepository(AnalyticsRecord::class)
            ->createQueryBuilder('a')
            ->getQuery()
            ->getResult();

        assert(is_array($records));

        /** @psalm-suppress MixedArgumentTypeCoercion */
        return $this->collectionFromRecords->create($records);
    }
}
