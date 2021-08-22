<?php

declare(strict_types=1);

namespace App\Context\Issues\Services;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\Services\FetchIssues\Factories\ExceptionHandlerFactory;
use App\Persistence\Entities\Issues\IssueRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Throwable;

use function array_map;

class FetchPublicIssues
{
    public function __construct(
        private EntityManager $entityManager,
        private ExceptionHandlerFactory $exceptionHandlerFactory,
    ) {
    }

    public function fetch(?FetchParams $fetchParams = null): IssuesResult
    {
        try {
            return $this->innerFetch(
                fetchParams: $fetchParams ??= new FetchParams()
            );
        } catch (Throwable $exception) {
            return new IssuesResult(
                absoluteTotal: 0,
                issueCollection: $this->exceptionHandlerFactory
                    ->getExceptionHandler()
                    ->handle(exception: $exception),
            );
        }
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function innerFetch(FetchParams $fetchParams): IssuesResult
    {
        $absoluteTotal = (int) $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.isPublic = true')
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();

        /** @var IssueRecord[] $issueRecords */
        $issueRecords = $this->entityManager
            ->getRepository(IssueRecord::class)
            ->createQueryBuilder('i')
            ->where('i.isPublic = true')
            ->orderBy('i.lastCommentAt', 'desc')
            ->setMaxResults($fetchParams->limit())
            ->setFirstResult($fetchParams->offset())
            ->getQuery()
            ->getResult();

        return new IssuesResult(
            absoluteTotal: $absoluteTotal,
            issueCollection: new IssueCollection(array_map(
                static fn (IssueRecord $i) => Issue::fromRecord(
                    record: $i,
                ),
                $issueRecords,
            )),
        );
    }
}
