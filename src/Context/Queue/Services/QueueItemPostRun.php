<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\Entities\QueueItemCollection;
use DateTimeImmutable;
use DateTimeZone;

use function count;

class QueueItemPostRun
{
    public function __construct(private SaveQueue $saveQueue)
    {
    }

    public function postRun(QueueItem $queueItem): void
    {
        $finishedAt = new DateTimeImmutable(
            timezone: new DateTimeZone('UTC'),
        );

        $newItems = new QueueItemCollection();

        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress PossiblyNullReference
         */
        foreach ($queueItem->queue()->queueItems()->toArray() as $item) {
            if ($item->id() !== $queueItem->id()) {
                $newItems->add($item);

                continue;
            }

            $newItems->add($item->withIsFinished()->withFinishedAt(
                $finishedAt
            ));
        }

        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress PossiblyNullReference
         */
        $queue = $queueItem->queue()->withIsRunning(false)
            ->withQueueItems($newItems);

        $totalItems = count($queue->queueItems());

        $finishedItems = $queue->finishedItemsCount();

        $queue = $queue->withPercentComplete(
            $finishedItems / $totalItems * 100
        );

        if ($finishedItems >= $totalItems) {
            $queue = $queue->withPercentComplete(100)
                ->withIsFinished(true)
                ->withFinishedAt($finishedAt);
        }

        $this->saveQueue->save($queue);
    }
}
