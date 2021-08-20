<?php

declare(strict_types=1);

namespace App\Context\Issues\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Events\SaveIssueBeforeValidate;
use App\Context\Issues\Services\SaveIssue\Factories\ExceptionHandlerFactory;
use App\Context\Issues\Services\SaveIssue\Factories\IssueValidityFactory;
use App\Context\Issues\Services\SaveIssue\Factories\SaveIssueFactory;
use App\Payload\Payload;
use App\Persistence\Entities\Issues\IssueRecord;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

class SaveIssue
{
    public function __construct(
        private EntityManager $entityManager,
        private SaveIssueFactory $saveIssueFactory,
        private EventDispatcherInterface $eventDispatcher,
        private IssueValidityFactory $issueValidityFactory,
        private ExceptionHandlerFactory $exceptionHandlerFactory,
    ) {
    }

    public function save(Issue $issue): Payload
    {
        try {
            return $this->innerSave(issue: $issue);
        } catch (Throwable $exception) {
            return $this->exceptionHandlerFactory->getExceptionHandler()
                ->handle(issue: $issue, exception: $exception);
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws ORMException
     * @throws Exception
     */
    public function innerSave(Issue $issue): Payload
    {
        $lastMessage = $issue->issueMessages()
            ->sort('createdAt', 'desc')
            ->first();

        $issue = $issue
            ->withUpdatedAt(updatedAt: new DateTimeImmutable(
                'now',
                new DateTimeZone('UTC'),
            ))
            ->withLastCommentAt(lastCommentAt: $lastMessage->createdAt())
            ->withLastCommentUserType(
                lastCommentUserType: $lastMessage->getCommentUserType(),
            );

        $record = $this->entityManager->find(
            IssueRecord::class,
            $issue->id(),
        );

        $beforeValidate = new SaveIssueBeforeValidate(
            issue: $issue,
            isNew: $record === null,
        );

        $this->eventDispatcher->dispatch($beforeValidate);

        $issue = $beforeValidate->issue;

        $validity = $this->issueValidityFactory->createIssueValidity(
            issue: $issue
        );

        return $this->saveIssueFactory->getSaveIssue(
            record: $record,
            validity: $validity,
        )->save(issue: $issue, record: $record, validity: $validity);
    }
}
