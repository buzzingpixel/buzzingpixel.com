<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\Events\SaveSoftwareAfterSave;
use App\Context\Software\Events\SaveSoftwareBeforeSave;
use App\Context\Software\Events\SaveSoftwareFailed;
use App\Payload\Payload;
use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\Entities\Software\SoftwareVersionRecord;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
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
use function assert;

class SaveSoftware
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(Software $software): Payload
    {
        try {
            return $this->innerSave($software);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving a user',
                ['exception' => $exception],
            );

            $this->eventDispatcher->dispatch(new SaveSoftwareFailed(
                $software,
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
    private function innerSave(Software $software): Payload
    {
        $payloadStatus = Payload::STATUS_UPDATED;

        $this->logger->info(
            'Checking for existing software by ID: ' . $software->id()
        );

        // Check if this is an existing record
        $record = $this->entityManager->find(
            SoftwareRecord::class,
            $software->id(),
        );

        $duplicate = null;

        // If it's not, make sure this software's slug is unique
        if ($record === null) {
            $this->logger->info(
                'The software does not exist by ID. Checking for ' .
                    'duplicate slug ' . $software->slug(),
            );

            $duplicate = (new SoftwareQueryBuilder())
                ->withSlug($software->slug())
                ->createQuery($this->entityManager)
                ->getOneOrNullResult();

            $payloadStatus = Payload::STATUS_CREATED;

            $this->logger->info(
                'Creating new software record',
            );

            $record = new SoftwareRecord();
        } else {
            $duplicate = (new SoftwareQueryBuilder())
                ->withSlug($software->slug())
                ->withId($software->id(), '!=')
                ->createQuery($this->entityManager)
                ->getOneOrNullResult();

            $this->logger->info(
                'This software record was found in the database. ' .
                'Updating existing software',
            );
        }

        assert(
            $duplicate === null ||
            $duplicate instanceof SoftwareRecord
        );

        if ($duplicate !== null) {
            $this->logger->warning(
                'The slug already exists in the system:' .
                $software->slug(),
            );

            return new Payload(
                Payload::STATUS_NOT_VALID,
                ['message' => 'Slug exists'],
            );
        }

        $beforeSave = new SaveSoftwareBeforeSave($software);

        $this->eventDispatcher->dispatch($beforeSave);

        $software = $beforeSave->software;

        $record->hydrateFromEntity($software);

        $record->setVersions(new ArrayCollection(
            array_map(
                function (SoftwareVersion $version) use (
                    $record
                ): SoftwareVersionRecord {
                    $versionRecord = $this->entityManager->find(
                        SoftwareVersionRecord::class,
                        $version->id(),
                    );

                    if ($versionRecord === null) {
                        $versionRecord = new SoftwareVersionRecord();
                    }

                    $versionRecord->hydrateFromEntity($version);

                    $versionRecord->setSoftware($record);

                    return $versionRecord;
                },
                $software->versions()->toArray(),
            ),
        ));

        $this->entityManager->persist($record);

        $this->entityManager->flush();

        $payload = new Payload(
            $payloadStatus,
            ['softwareEntity' => $software],
        );

        $afterAdd = new SaveSoftwareAfterSave(
            $software,
            $payload,
        );

        $this->eventDispatcher->dispatch($afterAdd);

        $this->logger->info('The software was saved');

        return $afterAdd->payload;
    }
}