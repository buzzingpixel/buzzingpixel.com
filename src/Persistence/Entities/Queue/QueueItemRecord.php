<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Queue;

use App\Context\Queue\Entities\QueueItem;
use App\Persistence\PropertyTraits\ClassName;
use App\Persistence\PropertyTraits\Context;
use App\Persistence\PropertyTraits\FinishedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsFinished;
use App\Persistence\PropertyTraits\MethodName;
use App\Persistence\PropertyTraits\RunOrder;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Mapping;
use LogicException;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\HasLifecycleCallbacks
 * @Mapping\Table(name="queue_items")
 */
class QueueItemRecord
{
    use Id;
    use RunOrder;
    use IsFinished;
    use FinishedAt;
    use ClassName;
    use MethodName;
    use Context;

    /**
     * Many queue items have one queue. This is the owning side.
     *
     * @Mapping\ManyToOne(
     *     targetEntity="QueueRecord",
     *     inversedBy="queueItems",
     *     cascade={"persist"},
     * )
     * @Mapping\JoinColumn(
     *     name="queue_id",
     *     referencedColumnName="id",
     * )
     */
    private QueueRecord $queue;

    /**
     * Returns null if queue ID has been set and doesn't match
     */
    public function getQueue(): ?QueueRecord
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (! isset($this->queue)) {
            return null;
        }

        if (
            $this->newQueueId !== null &&
            $this->queue->getId()->toString() !== $this->newQueueId
        ) {
            return null;
        }

        return $this->queue;
    }

    public function setQueue(QueueRecord $queue): void
    {
        $this->queue = $queue;
    }

    private ?string $newQueueId = null;

    public function getNewQueueId(): ?string
    {
        return $this->newQueueId;
    }

    public function setNewQueueId(string $newQueueId): void
    {
        $this->newQueueId = $newQueueId;
    }

    public function hydrateFromEntity(QueueItem $entity): void
    {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setRunOrder($entity->runOrder());
        $this->setIsFinished($entity->isFinished());
        $this->setFinishedAt($entity->finishedAt());
        $this->setClassName($entity->className());
        $this->setMethodName($entity->methodName());
        $this->setContext($entity->context());
    }

    /**
     * @Mapping\PreFlush
     */
    public function preFlushSetQueueFromNewId(PreFlushEventArgs $args): void
    {
        if ($this->getNewQueueId() === null) {
            return;
        }

        /**
         * @psalm-suppress RedundantCondition
         * @psalm-suppress RedundantPropertyInitializationCheck
         */
        if (
            isset($this->queue) &&
            $this->getNewQueueId() === $this->queue->getId()->toString()
        ) {
            return;
        }

        $queue = $args->getEntityManager()
            ->getRepository(QueueRecord::class)
            ->find($this->newQueueId);

        if ($queue === null) {
            throw new LogicException('No queue found');
        }

        $this->setQueue($queue);
    }
}
