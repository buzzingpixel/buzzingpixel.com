<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\SearchLicenses\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Entities\LicenseCollection;
use App\Context\Licenses\Entities\LicenseResult;
use App\Context\Licenses\Entities\SearchParams;
use App\Context\Licenses\Services\SearchLicenses\Contracts\SearchLicensesResultBuilderContract;
use App\Persistence\Entities\Licenses\LicenseRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

use function array_map;

class SearchLicensesResultBuilder implements SearchLicensesResultBuilderContract
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     *
     * @inheritDoc
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): LicenseResult {
        $absoluteTotal = (int) $this->entityManager
            ->getRepository(LicenseRecord::class)
            ->createQueryBuilder('l')
            ->where('l.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->select('count(l.id)')
            ->getQuery()
            ->getSingleScalarResult();

        /** @var LicenseRecord[] $records */
        $records = $this->entityManager
            ->getRepository(LicenseRecord::class)
            ->createQueryBuilder('l')
            ->where('l.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->setMaxResults($searchParams->limit())
            ->setFirstResult($searchParams->offset())
            ->getQuery()
            ->getResult();

        $intermediateCollection = new LicenseCollection(
            array_map(
                static fn (LicenseRecord $r) => License::fromRecord(
                    record: $r,
                ),
                $records,
            ),
        );

        $finalCollection = new LicenseCollection();

        foreach ($resultIds as $id) {
            $collection = $intermediateCollection->filter(
                static fn (License $l) => $l->id() === $id,
            );

            if ($collection->count() < 1) {
                continue;
            }

            $finalCollection->add($collection->first());
        }

        return new LicenseResult(
            absoluteTotal: $absoluteTotal,
            licenses: $finalCollection,
        );
    }
}
