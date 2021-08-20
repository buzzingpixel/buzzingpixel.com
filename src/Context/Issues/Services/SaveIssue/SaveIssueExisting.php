<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Events\SaveIssueAfterSave;
use App\Context\Issues\Events\SaveIssueBeforeSave;
use App\Context\Issues\Services\SaveIssue\Contracts\SaveIssueContract;
use App\Context\Issues\Services\SaveIssue\Contracts\ValidityContract;
use App\Payload\Payload;
use App\Persistence\Entities\Support\IssueRecord;
use Doctrine\ORM\EntityManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

use function assert;

class SaveIssueExisting implements SaveIssueContract
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
        assert($record instanceof IssueRecord);

        $this->logger->info('Saving existing Issue record');

        $beforeSave = new SaveIssueBeforeSave(issue: $issue, isNew: false);

        $this->eventDispatcher->dispatch($beforeSave);

        $issue = $beforeSave->issue;

        $record->hydrateFromEntity(
            entity: $issue,
            entityManager: $this->entityManager,
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->persist($record);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->flush();

        $payload = new Payload(
            status: Payload::STATUS_UPDATED,
            result: ['issueEntity' => $issue],
        );

        $afterSave = new SaveIssueAfterSave(
            issue: $issue,
            payload: $payload,
        );

        $this->eventDispatcher->dispatch($afterSave);

        $this->logger->info('The Issue was saved');

        return $afterSave->payload;
    }
}
