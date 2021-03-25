<?php

declare(strict_types=1);

namespace App\Context\Queue\Entities;

use App\EntityPropertyTraits\ClassName;
use App\EntityPropertyTraits\Context;
use App\EntityPropertyTraits\FinishedAt;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\IsFinished;
use App\EntityPropertyTraits\MethodName;
use App\EntityPropertyTraits\RunOrder;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Queue\QueueItemRecord;
use App\Persistence\Entities\Queue\QueueRecord;
use App\Utilities\DateTimeUtility;
use DateTimeInterface;
use LogicException;
use Ramsey\Uuid\UuidInterface;

use function assert;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class QueueItem
{
    use Id;
    use RunOrder;
    use IsFinished;
    use FinishedAt;
    use ClassName;
    use MethodName;
    use Context;

    private ?Queue $queue = null;

    public static function fromRecord(
        QueueItemRecord $record,
        ?Queue $queue = null,
    ): self {
        if ($queue === null) {
            $queueRecord = $record->getQueue();

            assert($queueRecord instanceof QueueRecord);

            $queue = Queue::fromRecord($queueRecord);
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
        ?Queue $queue = null,
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
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->runOrder = $runOrder;

        $this->isFinished = $isFinished;

        $this->finishedAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $finishedAt,
        );

        $this->className = $className;

        $this->methodName = $methodName;

        $this->context = $context;

        $this->queue = $queue;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function queue(): ?Queue
    {
        return $this->queue;
    }

    public function withQueue(Queue $queue): self
    {
        $clone = clone $this;

        $clone->queue = $queue;

        return $clone;
    }
}
