<?php

declare(strict_types=1);

namespace App\Context\Issues\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Services\FetchIssues\Factories\ExceptionHandlerFactory;
use App\Persistence\Entities\Support\IssueRecord;
use App\Persistence\QueryBuilders\Support\IssueQueryBuilder;
use Doctrine\ORM\EntityManager;
use Throwable;

use function array_map;

class FetchIssues
{
    public function __construct(
        private EntityManager $entityManager,
        private ExceptionHandlerFactory $exceptionHandlerFactory,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function fetch(IssueQueryBuilder $queryBuilder): IssueCollection
    {
        try {
            return $this->innerFetch(queryBuilder: $queryBuilder);
        } catch (Throwable $exception) {
            return $this->exceptionHandlerFactory->getExceptionHandler()
                ->handle($exception);
        }
    }

    /** @phpstan-ignore-next-line */
    private function innerFetch(
        IssueQueryBuilder $queryBuilder
    ): IssueCollection {
        /** @psalm-suppress MixedArgument */
        return new IssueCollection(array_map(
            static fn (IssueRecord $i) => Issue::fromRecord(
                record: $i,
            ),
            $queryBuilder->createQuery(
                entityManager: $this->entityManager,
            )->getResult(),
        ));
    }
}
