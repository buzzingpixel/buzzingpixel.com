<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserSessionEntity;
use App\Payload\Payload;
use App\Persistence\Entities\Users\UserSessionRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\Log\LoggerInterface;
use Throwable;

class SaveUserSession
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function save(UserSessionEntity $session): Payload
    {
        try {
            return $this->innerSave($session);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving a session',
                ['exception' => $exception],
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
    private function innerSave(UserSessionEntity $session): Payload
    {
        $payloadStatus = Payload::STATUS_UPDATED;

        $this->logger->info(
            'Checking for existing session by ID: ' . $session->id()
        );

        // Check if this is an existing record
        $record = $this->entityManager->find(
            UserSessionRecord::class,
            $session->id(),
        );

        // If it's not, make sure this user's email address is unique,
        // then create a new user record
        if ($record === null) {
            $payloadStatus = Payload::STATUS_CREATED;

            $this->logger->info(
                'The session does not exist by ID. Creating new record'
            );

            $record = new UserSessionRecord();
        } else {
            $this->logger->info(
                'This session was found in the database. Updating' .
                'existing session',
            );
        }

        $record->hydrateFromEntity($session);

        $this->entityManager->persist($record);

        $this->entityManager->flush($record);

        $this->logger->info(
            'The session was saved',
        );

        return new Payload(
            $payloadStatus,
            ['userSessionEntity' => $session]
        );
    }
}
