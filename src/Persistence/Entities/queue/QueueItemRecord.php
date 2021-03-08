<?php

declare(strict_types=1);

namespace App\Persistence\Entities\queue;

use App\Persistence\PropertyTraits\ClassName;
use App\Persistence\PropertyTraits\Context;
use App\Persistence\PropertyTraits\FinishedAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsFinished;
use App\Persistence\PropertyTraits\MethodName;
use App\Persistence\PropertyTraits\RunOrder;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
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
     * )
     * @Mapping\JoinColumn(
     *     name="queue_id",
     *     referencedColumnName="id",
     * )
     */
    private QueueRecord $queue;

    public function getQueue(): QueueRecord
    {
        return $this->queue;
    }

    public function setQueue(QueueRecord $queue): void
    {
        $this->queue = $queue;
    }
}
