<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItemEntity;
use App\Context\Queue\Events\AddToQueueAfterAdd;
use App\Context\Queue\Events\AddToQueueBeforeAdd;
use App\Context\Queue\Events\AddToQueueFailed;
use App\Payload\Payload;
use App\Persistence\Entities\Queue\QueueItemRecord;
use App\Persistence\Entities\Queue\QueueRecord;
use Config\General;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class SaveQueue
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(Queue $queue): Payload
    {
        try {
            return $this->innerSave($queue);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught adding to the queue',
                ['exception' => $exception],
            );

            $this->eventDispatcher->dispatch(new AddToQueueFailed(
                $queue,
                $exception,
            ));

            return new Payload(
                Payload::STATUS_ERROR,
                ['message' => $exception->getMessage()],
            );
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    private function innerSave(Queue $queue): Payload
    {
        $payloadStatus = Payload::STATUS_UPDATED;

        $this->logger->info(
            'Checking for existing queue by ID: ' . $queue->id()
        );

        // Check if this is an existing record
        $record = $this->entityManager->find(
            QueueRecord::class,
            $queue->id(),
        );

        // If it's not, create a new queue record
        if ($record === null) {
            $payloadStatus = Payload::STATUS_CREATED;

            $this->logger->info(
                'Creating new queue record',
            );

            $record = new QueueRecord();
        } else {
            $this->logger->info(
                'This queue record was found in the database. ' .
                'Updating existing queue',
            );
        }

        $beforeAdd = new AddToQueueBeforeAdd($queue);

        $this->eventDispatcher->dispatch($beforeAdd);

        $queue = $beforeAdd->queueEntity;

        $record->hydrateFromEntity($queue);

        $record->setQueueItems(new ArrayCollection(
            array_map(
                function (QueueItemEntity $item) use ($record): QueueItemRecord {
                    $itemRecord = $this->entityManager->find(
                        QueueItemRecord::class,
                        $item->id(),
                    );

                    if ($itemRecord === null) {
                        $itemRecord = new QueueItemRecord();
                    }

                    $itemRecord->hydrateFromEntity($item);

                    $itemRecord->setQueue($record);

                    return $itemRecord;
                },
                $queue->queueItems()->toArray(),
            )
        ));

        $this->entityManager->persist($record);

        $this->entityManager->flush();

        $payload = new Payload(
            $payloadStatus,
            ['queueEntity' => $queue]
        );

        $afterAdd = new AddToQueueAfterAdd(
            $queue,
            $payload,
        );

        $this->eventDispatcher->dispatch($afterAdd);

        $this->logger->info(
            'The queue was saved',
        );

        return $afterAdd->payload;
    }
}
