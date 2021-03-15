<?php

declare(strict_types=1);

namespace App\Context\Schedule\Services;

use App\Context\Schedule\Entities\ScheduleItem;
use App\Payload\Payload;
use App\Persistence\Entities\Schedule\ScheduleTrackingRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

class SaveScheduleItem
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function save(ScheduleItem $scheduleItem): Payload
    {
        try {
            return $this->innerSave($scheduleItem);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving a schedule item',
                ['exception' => $exception],
            );

            return new Payload(
                Payload::STATUS_ERROR,
                ['message' => $exception->getMessage()],
            );
        }
    }

    private function innerSave(ScheduleItem $scheduleItem): Payload
    {
        $payloadStatus = Payload::STATUS_UPDATED;

        $this->logger->info(
            'Checking for existing schedule item by ID: ' .
                $scheduleItem->id()
        );

        // Check if this is an existing record
        $record = $this->entityManager->find(
            ScheduleTrackingRecord::class,
            $scheduleItem->id(),
        );

        // If it's not, create a new user record
        if ($record === null) {
            $payloadStatus = Payload::STATUS_CREATED;

            $this->logger->info(
                'The schedule item does not exist by ID. ' .
                    'Creating new record',
            );

            $record = new ScheduleTrackingRecord();
        } else {
            $this->logger->info(
                'This schedule item was found in the database. ' .
                'Updating existing schedule item',
            );
        }

        $record->hydrateFromEntity($scheduleItem);

        $this->entityManager->persist($record);

        $this->entityManager->flush();

        $this->logger->info(
            'The schedule item was saved',
        );

        return new Payload($payloadStatus);
    }
}
