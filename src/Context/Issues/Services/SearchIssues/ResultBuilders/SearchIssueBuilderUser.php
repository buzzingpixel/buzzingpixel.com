<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues\ResultBuilders;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\Services\SearchIssues\Contracts\SearchIssuesResultBuilderContract;
use App\Context\Users\Entities\User;
use App\Persistence\Entities\Issues\IssueRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

use function array_map;
use function assert;
use function count;

class SearchIssueBuilderUser implements SearchIssuesResultBuilderContract
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
        ?User $user = null,
    ): IssuesResult {
        assert($user instanceof User);

        $absoluteTotalQuery = $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->andWhere('i.user = :userId')
            ->setParameter('userId', $user->id());

        if (count($fetchParams->statusFilter()) > 0) {
            $absoluteTotalQuery = $absoluteTotalQuery
                ->andWhere('i.status IN (:statuses)')
                ->setParameter(
                    'statuses',
                    $fetchParams->statusFilter()
                );
        }

        $absoluteTotal = (int) $absoluteTotalQuery
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $recordsQuery = $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->andWhere('i.user = :userId')
            ->setParameter('userId', $user->id());

        if (count($fetchParams->statusFilter()) > 0) {
            $recordsQuery = $recordsQuery
                ->andWhere('i.status IN (:statuses)')
                ->setParameter(
                    'statuses',
                    $fetchParams->statusFilter()
                );
        }

        /** @var IssueRecord[] $records */
        $records = $recordsQuery
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
            $collection = $intermediateCollection->filter(
                static fn (Issue $i) => $i->id() === $id,
            );

            if ($collection->count() < 1) {
                continue;
            }

            $finalCollection->add($collection->first());
        }

        return new IssuesResult(
            absoluteTotal: $absoluteTotal,
            issueCollection: $finalCollection,
        );
    }
}
