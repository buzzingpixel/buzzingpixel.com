<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalReplies;

use App\Context\Issues\Services\FetchTotalReplies\Factories\ExceptionHandlerFactory;
use App\Context\Issues\Services\FetchTotalReplies\Factories\FetchTotalRepliesQueryBuilderFactory;
use App\Persistence\QueryBuilders\Issues\IssueMessageQueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Throwable;

class FetchTotalReplies
{
    public function __construct(
        private EntityManager $entityManager,
        private ExceptionHandlerFactory $exceptionHandlerFactory,
        private FetchTotalRepliesQueryBuilderFactory $queryBuilderFactory,
    ) {
    }

    public function fetch(?IssueMessageQueryBuilder $queryBuilder = null): int
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
    private function innerFetch(
        ?IssueMessageQueryBuilder $queryBuilder = null,
    ): int {
        return (int) $this->queryBuilderFactory
            ->getQueryBuilder(queryBuilder: $queryBuilder)
            ->createQueryBuilder(entityManager: $this->entityManager)
            ->select('count(im.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
