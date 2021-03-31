<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\Events\DeleteSoftwareVersionAfterDelete;
use App\Context\Software\Events\DeleteSoftwareVersionBeforeDelete;
use App\Context\Software\Events\DeleteSoftwareVersionFailed;
use App\Payload\Payload;
use App\Persistence\Entities\Software\SoftwareVersionRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class DeleteSoftwareVersion
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function delete(SoftwareVersion $softwareVersion): Payload
    {
        try {
            return $this->innerDelete($softwareVersion);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught deleting software version',
                ['exception' => $exception],
            );

            $this->eventDispatcher->dispatch(
                new DeleteSoftwareVersionFailed(
                    $softwareVersion,
                    $exception,
                )
            );

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
    private function innerDelete(SoftwareVersion $softwareVersion): Payload
    {
        $this->logger->info(
            'Deleting software version by ID: ' .
                $softwareVersion->id()
        );

        $this->eventDispatcher->dispatch(
            new DeleteSoftwareVersionBeforeDelete(
                $softwareVersion
            ),
        );

        $record = $this->entityManager->find(
            SoftwareVersionRecord::class,
            $softwareVersion->id()
        );

        if ($record === null) {
            throw new LogicException(
                'Unable to find software version record to delete'
            );
        }

        $this->entityManager->remove($record);

        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(
            new DeleteSoftwareVersionAfterDelete(
                $softwareVersion
            ),
        );

        $this->logger->info('The software version was deleted');

        return new Payload(Payload::STATUS_DELETED);
    }
}
