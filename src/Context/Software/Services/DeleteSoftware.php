<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\Software;
use App\Context\Software\Events\DeleteSoftwareAfterDelete;
use App\Context\Software\Events\DeleteSoftwareBeforeDelete;
use App\Context\Software\Events\DeleteSoftwareFailed;
use App\Payload\Payload;
use App\Persistence\Entities\Software\SoftwareRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class DeleteSoftware
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function delete(Software $software): Payload
    {
        try {
            return $this->innerDelete($software);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught deleting software',
                ['exception' => $exception],
            );

            $this->eventDispatcher->dispatch(new DeleteSoftwareFailed(
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
    private function innerDelete(Software $software): Payload
    {
        $this->logger->info(
            'Deleting software by ID: ' . $software->id()
        );

        $this->eventDispatcher->dispatch(new DeleteSoftwareBeforeDelete(
            $software,
        ));

        $record = $this->entityManager->find(
            SoftwareRecord::class,
            $software->id()
        );

        if ($record === null) {
            throw new LogicException(
                'Unable to find software record to delete'
            );
        }

        $this->entityManager->remove($record);

        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new DeleteSoftwareAfterDelete(
            $software,
        ));

        $this->logger->info('The software was deleted');

        return new Payload(Payload::STATUS_DELETED);
    }
}
