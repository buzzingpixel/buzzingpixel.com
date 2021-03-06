<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserPasswordResetToken;
use App\Payload\Payload;
use App\Persistence\Entities\Users\UserPasswordResetTokenRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\Log\LoggerInterface;
use Throwable;

class SaveUserPasswordResetToken
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function save(UserPasswordResetToken $token): Payload
    {
        try {
            return $this->innerSave($token);
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
    private function innerSave(UserPasswordResetToken $token): Payload
    {
        $payloadStatus = Payload::STATUS_UPDATED;

        $this->logger->info(
            'Checking for existing password reset token by ID: ' . $token->id()
        );

        $record = $this->entityManager->find(
            UserPasswordResetTokenRecord::class,
            $token->id(),
        );

        if ($record === null) {
            $payloadStatus = Payload::STATUS_CREATED;

            $this->logger->info(
                'The token does not exist by ID. Creating new record'
            );

            $record = new UserPasswordResetTokenRecord();
        } else {
            $this->logger->info(
                'This token was found in the database. Updating' .
                'existing token',
            );
        }

        $record->hydrateFromEntity($token);

        $this->entityManager->persist($record);

        $this->entityManager->flush($record);

        $this->logger->info(
            'The token was saved',
        );

        return new Payload(
            $payloadStatus,
            ['userPasswordResetTokenRecord' => $record]
        );
    }
}
