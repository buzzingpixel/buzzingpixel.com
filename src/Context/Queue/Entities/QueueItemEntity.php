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

use function assert;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class QueueItemEntity
{
    private Id $id;
    private int $runOrder;
    private bool $isFinished;
    private ?DateTimeImmutable $finishedAt = null;
    private string $className;
    private string $methodName;
    /** @var mixed[] */
    private array $context;
    private ?QueueEntity $queue = null;

    public static function fromRecord(
        QueueItemRecord $record,
        ?QueueEntity $queue = null,
    ): self {
        if ($queue === null) {
            $queueRecord = $record->getQueue();

            assert($queueRecord instanceof QueueRecord);

            $queue = QueueEntity::fromRecord($queueRecord);
        }

        return new self(
            id: $record->getId()->toString(),
            runOrder: $record->getRunOrder(),
            isFinished: $record->getIsFinished(),
            className: $record->getClassName(),
            methodName: $record->getMethodName(),
            queue: $queue,
            context: $record->getContext(),
            finishedAt: $record->getFinishedAt(),
        );
    }

    /**
     * @param mixed[] $context
     */
    public function __construct(
        string $className,
        string $methodName = '__invoke',
        array $context = [],
        ?QueueEntity $queue = null,
        bool $isFinished = false,
        int $runOrder = 0,
        null | string | DateTimeInterface $finishedAt = null,
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

        $this->runOrder = $runOrder;

        $this->isFinished = $isFinished;

        $this->setFinishedAt($finishedAt);

        $this->className = $className;

        $this->methodName = $methodName;

        $this->context = $context;

        $this->queue = $queue;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    private function setFinishedAt(null | string | DateTimeInterface $finishedAt): void
    {
        if ($finishedAt === null) {
            $this->finishedAt = null;

            return;
        }

        if ($finishedAt instanceof DateTimeInterface) {
            $class = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $finishedAt->format(DateTimeInterface::ATOM),
            );
        } else {
            $class = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $finishedAt,
            );
        }

        assert($class instanceof DateTimeImmutable);

        $class = $class->setTimezone(
            new DateTimeZone('UTC')
        );

        $this->finishedAt = $class;
    }

    public function id(): string
    {
        return $this->id->toString();
    }

    public function runOrder(): int
    {
        return $this->runOrder;
    }

    public function withRunOrder(int $runOrder): self
    {
        $clone = clone $this;

        $clone->runOrder = $runOrder;

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

    public function finishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function withFinishedAt(null | string | DateTimeInterface $finishedAt): self
    {
        $clone = clone $this;

        $clone->setFinishedAt($finishedAt);

        return $clone;
    }

    public function className(): string
    {
        return $this->className;
    }

    public function withClassName(string $className): self
    {
        $clone = clone $this;

        $clone->className = $className;

        return $clone;
    }

    public function methodName(): string
    {
        return $this->methodName;
    }

    public function withMethodName(string $methodName): self
    {
        $clone = clone $this;

        $clone->methodName = $methodName;

        return $clone;
    }

    /**
     * @return mixed[]
     */
    public function context(): array
    {
        return $this->context;
    }

    /**
     * @param mixed[] $context
     */
    public function withContext(array $context): self
    {
        $clone = clone $this;

        $clone->context = $context;

        return $clone;
    }

    public function queue(): ?QueueEntity
    {
        return $this->queue;
    }

    public function withQueue(QueueEntity $queue): self
    {
        $clone = clone $this;

        $clone->queue = $queue;

        return $clone;
    }
}
