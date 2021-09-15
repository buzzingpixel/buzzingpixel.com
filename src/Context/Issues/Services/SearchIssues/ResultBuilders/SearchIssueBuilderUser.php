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

        $absoluteTotal = (int) $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->andWhere('i.user = :userId')
            ->setParameter('userId', $user->id())
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();

        /** @var IssueRecord[] $records */
        $records = $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->andWhere('i.user = :userId')
            ->setParameter('userId', $user->id())
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
