<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Context\Licenses\Contracts\SaveLicense;
use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Events\SaveLicenseAfterSave;
use App\Context\Licenses\Events\SaveLicenseBeforeSave;
use App\Context\Licenses\Factories\LicenseValidityFactory;
use App\Payload\Payload;
use App\Persistence\Entities\Licenses\LicenseRecord;
use Doctrine\ORM\EntityManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

use function implode;

class SaveLicenseNew implements SaveLicense
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManager $entityManager,
        private EventDispatcherInterface $eventDispatcher,
        private LicenseValidityFactory $licenseValidityFactory,
    ) {
    }

    public function save(License $license): Payload
    {
        $this->logger->info(
            'Creating new License record'
        );

        $validity = $this->licenseValidityFactory->createLicenseValidity(
            license: $license
        );

        if (! $validity->isValid()) {
            $this->logger->error(
                'The License entity is invalid',
                [
                    'licenseEntity' => $license,
                    'licenseValidity' => $validity,
                ],
            );

            return new Payload(
                status: $validity->payloadStatusText(),
                result: [
                    'message' => implode(
                        ' ',
                        $validity->validationErrors()
                    ),
                ],
            );
        }

        $beforeSave = new SaveLicenseBeforeSave(license: $license);

        $this->eventDispatcher->dispatch($beforeSave);

        $license = $beforeSave->license;

        $record = new LicenseRecord();

        $record->hydrateFromEntity(
            entity: $license,
            entityManager: $this->entityManager,
        );

        $this->entityManager->persist($record);

        $this->entityManager->flush();

        $payload = new Payload(
            status: Payload::STATUS_CREATED,
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
