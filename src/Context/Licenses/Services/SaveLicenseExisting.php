<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Context\Licenses\Contracts\SaveLicense;
use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Events\SaveLicenseAfterSave;
use App\Context\Licenses\Events\SaveLicenseBeforeSave;
use App\Payload\Payload;
use App\Persistence\Entities\Licenses\LicenseRecord;
use Doctrine\ORM\EntityManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

use function assert;

class SaveLicenseExisting implements SaveLicense
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManager $entityManager,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(
        License $license,
        ?LicenseRecord $licenseRecord = null,
    ): Payload {
        assert($licenseRecord instanceof LicenseRecord);

        $this->logger->info(
            'Saving existing License record'
        );

        $beforeSave = new SaveLicenseBeforeSave(license: $license);

        $this->eventDispatcher->dispatch($beforeSave);

        $license = $beforeSave->license;

        $licenseRecord->hydrateFromEntity(
            entity: $license,
            entityManager: $this->entityManager,
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->persist($licenseRecord);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->entityManager->flush();

        $payload = new Payload(
            status: Payload::STATUS_UPDATED,
            result: ['licenseEntity' => $license],
        );

        $afterSave = new SaveLicenseAfterSave(
            license: $license,
            payload: $payload,
        );

        $this->eventDispatcher->dispatch($afterSave);

        $this->logger->info('The License was saved');

        return $afterSave->payload;
    }
}
