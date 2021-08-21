<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues\ResultBuilders;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Services\SearchIssues\Contracts\SearchUserIssuesResultBuilderContract;
use App\Persistence\Entities\Issues\IssueRecord;
use Doctrine\ORM\EntityManager;

use function array_map;
use function assert;
use function is_array;

class SearchIssueBuilder implements SearchUserIssuesResultBuilderContract
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function buildResult(array $resultIds): IssueCollection
    {
        $records = $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->getQuery()
            ->getResult();

        assert(is_array($records));

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

        return $finalCollection;
    }
}
