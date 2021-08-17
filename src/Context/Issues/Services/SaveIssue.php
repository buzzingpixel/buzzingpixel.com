<?php

declare(strict_types=1);

namespace App\Context\Issues\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\SaveIssue\Factories\ExceptionHandlerFactory;
use App\Context\Issues\Services\SaveIssue\Factories\IssueValidityFactory;
use App\Context\Issues\Services\SaveIssue\Factories\SaveIssueFactory;
use App\Payload\Payload;
use App\Persistence\Entities\Support\IssueRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Throwable;

class SaveIssue
{
    public function __construct(
        private EntityManager $entityManager,
        private SaveIssueFactory $saveIssueFactory,
        private IssueValidityFactory $issueValidityFactory,
        private ExceptionHandlerFactory $exceptionHandlerFactory,
    ) {
    }

    public function save(Issue $issue): Payload
    {
        try {
            return $this->innerSave($issue);
        } catch (Throwable $exception) {
            return $this->exceptionHandlerFactory->getExceptionHandler()
                ->handle(issue: $issue, exception: $exception);
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws ORMException
     */
    public function innerSave(Issue $issue): Payload
    {
        $record = $this->entityManager->find(
            IssueRecord::class,
            $issue->id(),
        );

        $validity = $this->issueValidityFactory->createIssueValidity(
            issue: $issue
        );

        return $this->saveIssueFactory->getSaveIssue(
            record: $record,
            validity: $validity,
        )->save(issue: $issue, record: $record, validity: $validity);
    }
}
