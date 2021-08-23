<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues\ResultBuilders;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\Services\SearchIssues\Contracts\SearchUserIssuesResultBuilderContract;
use App\Persistence\Entities\Issues\IssueRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

use function array_map;

class SearchIssueBuilder implements SearchUserIssuesResultBuilderContract
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
        FetchParams $fetchParams,
    ): IssuesResult {
        $absoluteTotal = (int) $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();

        /** @var IssueRecord[] $records */
        $records = $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->setMaxResults($fetchParams->limit())
            ->setFirstResult($fetchParams->offset())
            ->getQuery()
            ->getResult();

        $intermediateCollection = new IssueCollection(
            array_map(
                static fn (IssueRecord $r) => Issue::fromRecord(
                    record: $r,
                ),
                $records
            ),
        );

        $finalCollection = new IssueCollection();

        foreach ($resultIds as $id) {
            $finalCollection->add(
                $intermediateCollection->filter(
                    static fn (Issue $i) => $i->id() === $id,
                )->first(),
            );
        }

        return new IssuesResult(
            absoluteTotal: $absoluteTotal,
            issueCollection: $finalCollection,
        );
    }
}
