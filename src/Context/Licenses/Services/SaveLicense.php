<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Events\SaveLicenseFailed;
use App\Context\Licenses\Factories\SaveLicenseFactory;
use App\Payload\Payload;
use App\Persistence\Entities\Licenses\LicenseRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class SaveLicense
{
    public function __construct(
        private General $config,
        private LoggerInterface $logger,
        private EntityManager $entityManager,
        private SaveLicenseFactory $saveLicenseFactory,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(License $license): Payload
    {
        try {
            return $this->innerSave($license);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                /** @noinspection PhpUnhandledExceptionInspection */
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving a license',
                ['exception' => $exception],
            );

            $this->eventDispatcher->dispatch(new SaveLicenseFailed(
                license: $license,
                exception: $exception,
            ));

            return new Payload(
                status: Payload::STATUS_ERROR,
                result: ['message' => $exception->getMessage()],
            );
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    private function innerSave(License $license): Payload
    {
        $this->logger->info(
            'Checking for existing license by ID: ' . $license->id()
        );

        $licenseRecord = $this->entityManager->find(
            LicenseRecord::class,
            $license->id(),
        );

        return $this->saveLicenseFactory
            ->createSaveLicense($licenseRecord)
            ->save($license, $licenseRecord);
    }
}
