<?php

declare(strict_types=1);

namespace App\Context\Issues\Services;

use App\Context\Issues\Services\FetchTotalIssues\Factories\ExceptionHandlerFactory;
use App\Context\Issues\Services\FetchTotalIssues\Factories\FetchTotalIssuesQueryBuilderFactory;
use App\Persistence\QueryBuilders\Issues\IssueQueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Throwable;

class FetchTotalIssues
{
    public function __construct(
        private EntityManager $entityManager,
        private ExceptionHandlerFactory $exceptionHandlerFactory,
        private FetchTotalIssuesQueryBuilderFactory $queryBuilderFactory,
    ) {
    }

    public function fetch(?IssueQueryBuilder $queryBuilder = null): int
    {
        try {
            return $this->innerFetch(queryBuilder: $queryBuilder);
        } catch (Throwable $exception) {
            return $this->exceptionHandlerFactory->getExceptionHandler()
                ->handle(exception: $exception);
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function innerFetch(?IssueQueryBuilder $queryBuilder): int
    {
        return (int) $this->queryBuilderFactory
            ->getQueryBuilder(queryBuilder: $queryBuilder)
            ->createQueryBuilder(entityManager: $this->entityManager)
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
