<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Events\SaveUserAfterSave;
use App\Context\Users\Events\SaveUserBeforeSave;
use App\Context\Users\Events\SaveUserFailed;
use App\Payload\Payload;
use App\Persistence\Entities\Users\UserRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function implode;

class SaveUser
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(UserEntity $user): Payload
    {
        try {
            return $this->innerSave($user);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving a user',
                ['exception' => $exception],
            );

            $this->eventDispatcher->dispatch(new SaveUserFailed(
                $user,
                $exception,
            ));

            return new Payload(
                Payload::STATUS_ERROR,
                ['message' => $exception->getMessage()],
            );
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    private function innerSave(UserEntity $user): Payload
    {
        $payloadStatus = Payload::STATUS_UPDATED;

        $this->logger->info(
            'Checking for existing user by ID: ' . $user->id()
        );

        // Check if this is an existing record
        $userRecord = $this->entityManager->find(
            UserRecord::class,
            $user->id(),
        );

        // If it's not, make sure this user's email address is unique,
        // then create a new user record
        if ($userRecord === null) {
            $this->logger->info(
                'The user does not exist by ID. Checking for ' .
                'duplicate email address ' . $user->emailAddress()
            );

            $dql = implode('', [
                'SELECT u FROM ',
                UserRecord::class,
                ' u WHERE u.emailAddress = :emailAddress',
            ]);

            $query = $this->entityManager->createQuery($dql);

            $query->setParameter(
                'emailAddress',
                $user->emailAddress(),
            );

            $duplicate = $query->getOneOrNullResult();

            if ($duplicate !== null) {
                $this->logger->warning(
                    'The email address already exists in the system:' .
                    $user->emailAddress(),
                );

                return new Payload(
                    Payload::STATUS_NOT_VALID,
                    ['message' => 'Email address exists'],
                );
            }

            $payloadStatus = Payload::STATUS_CREATED;

            $this->logger->info(
                'No duplicate email was found, creating new ' .
                'user record',
            );

            $userRecord = new UserRecord();
        } else {
            $this->logger->info(
                'This user was found in the database. Updating' .
                'existing user',
            );
        }

        $beforeSave = new SaveUserBeforeSave(
            $user
        );

        $this->eventDispatcher->dispatch($beforeSave);

        $userRecord->hydrateFromEntity($beforeSave->userEntity);

        $this->entityManager->persist($userRecord);

        $this->entityManager->flush($userRecord);

        $payload = new Payload(
            $payloadStatus,
            [
                'userEntity' => $beforeSave->userEntity,
            ]
        );

        $afterSave = new SaveUserAfterSave(
            $beforeSave->userEntity,
            $payload,
        );

        $this->eventDispatcher->dispatch($afterSave);

        $this->logger->info(
            'The user was saved',
        );

        return $afterSave->payload;
    }
}
