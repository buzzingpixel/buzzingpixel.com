<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Contracts\SaveAnalyticContract;
use App\Context\Analytics\Entities\AnalyticsEntity;
use App\Payload\Payload;
use App\Persistence\Entities\Analytics\AnalyticsRecord;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class SaveAnalyticNew implements SaveAnalyticContract
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManager $entityManager,
    ) {
    }

    public function save(
        AnalyticsEntity $entity,
        ?AnalyticsRecord $record = null,
    ): Payload {
        $this->logger->info('Creating new analytic record');

        $record = new AnalyticsRecord();

        $record->hydrateFromEntity(
            entity: $entity,
            entityManager: $this->entityManager
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->persist($record);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->flush();

        $this->logger->info('The analytic record was saved');

        return new Payload(
            status: Payload::STATUS_CREATED,
            result: ['analyticsEntity' => $entity],
        );
    }
}
