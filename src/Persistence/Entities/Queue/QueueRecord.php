<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Queue;

use App\Context\Queue\Entities\QueueEntity;
use App\Persistence\PropertyTraits\AddedAt;
use App\Persistence\PropertyTraits\AssumeDeadAfter;
use App\Persistence\PropertyTraits\ErrorMessage;
use App\Persistence\PropertyTraits\FinishedAt;
use App\Persistence\PropertyTraits\FinishedDueToError;
use App\Persistence\PropertyTraits\Handle;
use App\Persistence\PropertyTraits\HasStarted;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\InitialAssumeDeadAfter;
use App\Persistence\PropertyTraits\IsFinished;
use App\Persistence\PropertyTraits\IsRunning;
use App\Persistence\PropertyTraits\PercentComplete;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="queue")
 * @psalm-suppress PropertyNotSetInConstructor
 */
class QueueRecord
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

    /**
     * One queue has many queue items. This is the inverse side.
     *
     * @var Collection<int, QueueItemRecord>
     * @Mapping\OneToMany(
     *     targetEntity="QueueItemRecord",
     *     mappedBy="queue",
     *     cascade={"persist", "remove"},
     * )
     * @Mapping\OrderBy({"runOrder" = "asc"})
     */
    private Collection $queueItems;

    /**
     * @return Collection<int, QueueItemRecord>
     */
    public function getQueueItems(): Collection
    {
        return $this->queueItems;
    }

    /**
     * @param Collection<int, QueueItemRecord> $queueItems
     */
    public function setQueueItems(Collection $queueItems): void
    {
        $this->queueItems = $queueItems;
    }

    public function __construct()
    {
        $this->queueItems = new ArrayCollection();
    }

    public function hydrateFromEntity(QueueEntity $entity): self
    {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setHandle($entity->handle());
        $this->setHasStarted($entity->hasStarted());
        $this->setIsRunning($entity->isRunning());
        $this->setAssumeDeadAfter($entity->assumeDeadAfter());
        $this->setInitialAssumeDeadAfter(
            $entity->initialAssumeDeadAfter()
        );
        $this->setIsFinished($entity->isFinished());
        $this->setFinishedDueToError(
            $entity->finishedDueToError()
        );
        $this->setErrorMessage($entity->errorMessage());
        $this->setPercentComplete($entity->percentComplete());
        $this->setAddedAt($entity->addedAt());
        $this->setFinishedAt($entity->finishedAt());

        return $this;
    }
}
