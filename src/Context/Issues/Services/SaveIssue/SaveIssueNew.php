<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Events\SaveIssueAfterSave;
use App\Context\Issues\Events\SaveIssueBeforeSave;
use App\Context\Issues\Services\SaveIssue\Contracts\SaveIssueContract;
use App\Context\Issues\Services\SaveIssue\Contracts\ValidityContract;
use App\Payload\Payload;
use App\Persistence\Entities\Issues\IssueRecord;
use Doctrine\ORM\EntityManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class SaveIssueNew implements SaveIssueContract
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManager $entityManager,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(
        Issue $issue,
        ?IssueRecord $record,
        ValidityContract $validity,
    ): Payload {
        $this->logger->info('Creating new Issue record');

        $beforeSave = new SaveIssueBeforeSave(issue: $issue, isNew: true);

        $this->eventDispatcher->dispatch($beforeSave);

        $issue = $beforeSave->issue;

        $record = new IssueRecord();

        $record->hydrateFromEntity(
            entity: $issue,
            entityManager: $this->entityManager,
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->persist($record);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->flush();

        $payload = new Payload(
            status: Payload::STATUS_CREATED,
            result: ['issueEntity' => $issue],
        );

        $afterSave = new SaveIssueAfterSave(
            issue: $issue,
            payload: $payload,
            wasNew: true,
        );

        $this->eventDispatcher->dispatch($afterSave);

        $this->logger->info('The Issue was saved');

        return $afterSave->payload;
    }
}
