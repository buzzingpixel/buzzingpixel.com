<?php

declare(strict_types=1);

namespace App\Context\Queue\Entities;

use App\EntityValueObjects\Id;
use App\Persistence\Entities\Queue\QueueItemRecord;
use App\Persistence\Entities\Queue\QueueRecord;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;
use Ramsey\Uuid\UuidInterface;

use function array_map;
use function array_merge;
use function assert;
use function is_array;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class QueueEntity
{
    private Id $id;
    private string $handle;
    private bool $hasStarted;
    private bool $isRunning;
    private DateTimeImmutable $assumeDeadAfter;
    private DateTimeImmutable $initialAssumeDeadAfter;
    private bool $isFinished;
    private bool $finishedDueToError;
    private ?string $errorMessage;
    private float | int $percentComplete;
    private DateTimeImmutable $addedAt;
    private ?DateTimeImmutable $finishedAt;
    /** @phpstan-ignore-next-line  */
    private QueueItemCollection $queueItems;

    public static function fromRecord(QueueRecord $record): self
    {
        return (new QueueEntity(
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
            $this->id = Id::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = Id::fromString($id->toString());
        } else {
            $this->id = Id::fromString($id);
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
            $this->assumeDeadAfter = $this->createDateTimeImmutable(
                $assumeDeadAfter,
            );
        }

        if ($initialAssumeDeadAfter === null) {
            $this->initialAssumeDeadAfter = $this->assumeDeadAfter;
        } else {
            $this->initialAssumeDeadAfter = $this->createDateTimeImmutable(
                $initialAssumeDeadAfter,
            );
        }

        $this->addedAt = $this->createDateTimeImmutable($addedAt);

        $this->finishedAt = $this->createDateTimeImmutableOrNull(
            $finishedAt
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

    private function createDateTimeImmutableOrNull(
        null | string | DateTimeInterface $dateTime,
    ): ?DateTimeImmutable {
        if ($dateTime === null) {
            return null;
        }

        return $this->createDateTimeImmutable($dateTime);
    }

    private function createDateTimeImmutable(
        null | string | DateTimeInterface $dateTime,
    ): DateTimeImmutable {
        if ($dateTime === null) {
            return new DateTimeImmutable(
                timezone: new DateTimeZone('UTC'),
            );
        }

        if ($dateTime instanceof DateTimeInterface) {
            $class = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $dateTime->format(DateTimeInterface::ATOM),
            );
        } else {
            $class = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $dateTime,
            );
        }

        assert($class instanceof DateTimeImmutable);

        $class = $class->setTimezone(
            new DateTimeZone('UTC')
        );

        return $class;
    }

    public function id(): string
    {
        return $this->id->toString();
    }

    public function handle(): string
    {
        return $this->handle;
    }

    public function withHandle(string $handle): self
    {
        $clone = clone $this;

        $clone->handle = $handle;

        return $clone;
    }

    public function hasStarted(): bool
    {
        return $this->hasStarted;
    }

    public function withHasStarted(bool $hasStarted): self
    {
        $clone = clone $this;

        $clone->hasStarted = $hasStarted;

        return $clone;
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function withIsRunning(bool $isRunning = true): self
    {
        $clone = clone $this;

        $clone->isRunning = $isRunning;

        return $clone;
    }

    public function assumeDeadAfter(): DateTimeImmutable
    {
        return $this->assumeDeadAfter;
    }

    public function withAssumeDeadAfter(
        DateTimeImmutable $assumeDeadAfter,
    ): self {
        $clone = clone $this;

        $clone->assumeDeadAfter = $assumeDeadAfter;

        return $clone;
    }

    public function initialAssumeDeadAfter(): DateTimeImmutable
    {
        return $this->initialAssumeDeadAfter;
    }

    public function withInitialAssumeDeadAfter(
        DateTimeImmutable $initialAssumeDeadAfter,
    ): self {
        $clone = clone $this;

        $clone->initialAssumeDeadAfter = $initialAssumeDeadAfter;

        return $clone;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function withIsFinished(bool $isFinished = true): self
    {
        $clone = clone $this;

        $clone->isFinished = $isFinished;

        return $clone;
    }

    public function finishedDueToError(): bool
    {
        return $this->finishedDueToError;
    }

    public function withFinishedDueToError(bool $finishedDueToError = true): self
    {
        $clone = clone $this;

        $clone->finishedDueToError = $finishedDueToError;

        return $clone;
    }

    public function errorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function withErrorMessage(?string $errorMessage): self
    {
        $clone = clone $this;

        $clone->errorMessage = $errorMessage;

        return $clone;
    }

    public function percentComplete(): float | int
    {
        return $this->percentComplete;
    }

    public function withPercentComplete(float | int $percentComplete): self
    {
        $clone = clone $this;

        $clone->percentComplete = $percentComplete;

        return $clone;
    }

    public function addedAt(): DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function withAddedAt(DateTimeImmutable $addedAt): self
    {
        $clone = clone $this;

        $clone->addedAt = $addedAt;

        return $clone;
    }

    public function finishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function withFinishedAt(?DateTimeImmutable $finishedAt): self
    {
        $clone = clone $this;

        $clone->finishedAt = $finishedAt;

        return $clone;
    }

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
            static fn (QueueItemEntity $e) => $e->withQueue(
                $clone
            ),
            array_merge($queueItems)
        ));

        return $clone;
    }

    public function withAddedQueueItem(QueueItemEntity $newQueueItem): self
    {
        $clone = clone $this;

        $clone->queueItems = new QueueItemCollection(array_map(
            static fn (QueueItemEntity $e) => $e->withQueue(
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
            static fn (QueueItemRecord $r) => QueueItemEntity::fromRecord(
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

        $queueItems->add(QueueItemEntity::fromRecord(
            $record,
            $clone
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
