<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Entities\AnalyticsEntity;
use App\Context\Analytics\Factories\SaveAnalyticFactory;
use App\Payload\Payload;
use App\Persistence\Entities\Analytics\AnalyticsRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\Log\LoggerInterface;
use Throwable;

class SaveAnalytic
{
    public function __construct(
        private General $config,
        private LoggerInterface $logger,
        private EntityManager $entityManager,
        private SaveAnalyticFactory $saveAnalyticFactory,
    ) {
    }

    public function save(AnalyticsEntity $analytic): Payload
    {
        try {
            return $this->innerSave($analytic);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                /** @noinspection PhpUnhandledExceptionInspection */
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving analytics',
                ['exception' => $exception],
            );

            return new Payload(
                status: Payload::STATUS_ERROR,
                result: ['message' => $exception->getMessage()],
            );
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws ORMException
     */
    private function innerSave(AnalyticsEntity $analytic): Payload
    {
        $this->logger->info(
            'Checking for existing analytic by ID: ' .
                $analytic->id()
        );

        $record = $this->entityManager->find(
            AnalyticsRecord::class,
            $analytic->id(),
        );

        return $this->saveAnalyticFactory
            ->createSaveAnalytic(record: $record)
            ->save(entity: $analytic, record: $record);
    }
}
