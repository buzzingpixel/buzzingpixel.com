<?php

declare(strict_types=1);

namespace App\Context\Queue\Entities;

use App\EntityPropertyTraits\AddedAt;
use App\EntityPropertyTraits\AssumeDeadAfter;
use App\EntityPropertyTraits\ErrorMessage;
use App\EntityPropertyTraits\FinishedAt;
use App\EntityPropertyTraits\FinishedDueToError;
use App\EntityPropertyTraits\Handle;
use App\EntityPropertyTraits\HasStarted;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\InitialAssumeDeadAfter;
use App\EntityPropertyTraits\IsFinished;
use App\EntityPropertyTraits\IsRunning;
use App\EntityPropertyTraits\PercentComplete;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Queue\QueueItemRecord;
use App\Persistence\Entities\Queue\QueueRecord;
use App\Utilities\DateTimeUtility;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;
use Ramsey\Uuid\UuidInterface;

use function array_map;
use function array_merge;
use function is_array;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class Queue
{
    use Id;
    use Handle;
    use HasStarted;
    use IsRunning;
    use AssumeDeadAfter;
    use InitialAssumeDeadAfter;
    use IsFinished;
    use FinishedDueToError;
    use ErrorMessage;
    use PercentComplete;
    use AddedAt;
    use FinishedAt;

    /** @phpstan-ignore-next-line  */
    private QueueItemCollection $queueItems;

    public static function fromRecord(QueueRecord $record): self
    {
        return (new Queue(
            id: $record->getId()->toString(),
            handle: $record->getHandle(),
            hasStarted: $record->getHasStarted(),
            isRunning: $record->getIsRunning(),
            assumeDeadAfter: $record->getAssumeDeadAfter(),
            initialAssumeDeadAfter: $record->getInitialAssumeDeadAfter(),
            isFinished: $record->getIsFinished(),
            finishedDueToError: $record->getFinishedDueToError(),
            errorMessage: $record->getErrorMessage(),
            percentComplete: $record->getPercentComplete(),
            addedAt: $record->getAddedAt(),
            finishedAt: $record->getFinishedAt(),
        ))->withQueueItemsFromRecord($record);
    }

    /** @phpstan-ignore-next-line  */
    public function __construct(
        string $handle = 'default-handle',
        bool $hasStarted = false,
        bool $isRunning = false,
        bool $isFinished = false,
        bool $finishedDueToError = false,
        ?string $errorMessage = null,
        float | int $percentComplete = 0,
        null | string | DateTimeInterface $assumeDeadAfter = null,
        null | string | DateTimeInterface $initialAssumeDeadAfter = null,
        null | string | DateTimeInterface $addedAt = null,
        null | string | DateTimeInterface $finishedAt = null,
        null | array | QueueItemCollection $queueItems = null,
        null | string | UuidInterface $id = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->handle = $handle;

        $this->hasStarted = $hasStarted;

        $this->isRunning = $isRunning;

        $this->isFinished = $isFinished;

        $this->finishedDueToError = $finishedDueToError;

        $this->errorMessage = $errorMessage;

        $this->percentComplete = $percentComplete;

        if ($assumeDeadAfter === null) {
            $this->assumeDeadAfter = new DateTimeImmutable(
                datetime: '+ 15 minutes',
                timezone: new DateTimeZone('UTC'),
            );
        } else {
            $this->assumeDeadAfter = DateTimeUtility::createDateTimeImmutable(
                $assumeDeadAfter,
            );
        }

        if ($initialAssumeDeadAfter === null) {
            $this->initialAssumeDeadAfter = $this->assumeDeadAfter;
        } else {
            $this->initialAssumeDeadAfter = DateTimeUtility::createDateTimeImmutable(
                $initialAssumeDeadAfter,
            );
        }

        $this->addedAt = DateTimeUtility::createDateTimeImmutable(
            $addedAt,
        );

        $this->finishedAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $finishedAt,
        );

        if ($queueItems === null) {
            $this->queueItems = new QueueItemCollection();
        } elseif (is_array($queueItems)) {
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $this->queueItems = new QueueItemCollection($queueItems);
        } else {
            $this->queueItems = $queueItems;
        }

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    /** @phpstan-ignore-next-line  */
    public function queueItems(): QueueItemCollection
    {
        return $this->queueItems;
    }

    /** @phpstan-ignore-next-line  */
    public function withQueueItems(array | QueueItemCollection $queueItems): self
    {
        $clone = clone $this;

        if (! is_array($queueItems)) {
            $queueItems = $queueItems->toArray();
        }

        $clone->queueItems = new QueueItemCollection(array_map(
            static fn (QueueItem $e) => $e->withQueue(
                $clone
            ),
            array_merge($queueItems)
        ));

        return $clone;
    }

    public function withAddedQueueItem(QueueItem $newQueueItem): self
    {
        $clone = clone $this;

        $clone->queueItems = new QueueItemCollection(array_map(
            static fn (QueueItem $e) => $e->withQueue(
                $clone
            ),
            array_merge(
                $this->queueItems->toArray(),
                [$newQueueItem]
            ),
        ));

        return $clone;
    }

    public function withQueueItemsFromRecord(QueueRecord $record): self
    {
        $clone = clone $this;

        $clone->queueItems = new QueueItemCollection(array_map(
            static fn (QueueItemRecord $r) => QueueItem::fromRecord(
                $r,
                $clone,
            ),
            $record->getQueueItems()->toArray(),
        ));

        return $clone;
    }

    public function withQueueItemFromItemRecord(QueueItemRecord $record): self
    {
        $clone = clone $this;

        $queueItems = new QueueItemCollection(
            $this->queueItems->toArray(),
        );

        $queueItems->add(QueueItem::fromRecord(
            $record,
            $clone,
        ));

        $clone->queueItems = $queueItems;

        return $clone;
    }

    public function finishedItemsCount(): int
    {
        $finished = 0;

        foreach ($this->queueItems->toArray() as $item) {
            if (! $item->isFinished()) {
                continue;
            }

            $finished++;
        }

        return $finished;
    }
}
