<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\UserEntity;
use App\Context\Users\Events\DeleteUserAfterDelete;
use App\Context\Users\Events\DeleteUserBeforeDelete;
use App\Context\Users\Events\DeleteUserFailed;
use App\Payload\Payload;
use App\Persistence\Entities\Users\UserPasswordResetTokenRecord;
use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\Entities\Users\UserSessionRecord;
use Config\General;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class DeleteUser
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function delete(UserEntity $user): Payload
    {
        try {
            return $this->innerDelete($user);
        } catch (Throwable $exception) {
            try {
                $this->entityManager->getConnection()->rollBack();
            } catch (Throwable) {
            }

            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught deleting a user',
                ['exception' => $exception],
            );

            $this->eventDispatcher->dispatch(new DeleteUserFailed(
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
     * @throws ConnectionException
     */
    private function innerDelete(UserEntity $user): Payload
    {
        $this->logger->info(
            'Deleting user by ID: ' . $user->id()
        );

        $this->eventDispatcher->dispatch(new DeleteUserBeforeDelete(
            $user,
        ));

        $this->entityManager->getConnection()->beginTransaction();

        $this->deleteUserSessions($user);

        $this->deletePasswordResetTokens($user);

        $this->deleteUser($user);

        $this->entityManager->getConnection()->commit();

        $this->eventDispatcher->dispatch(new DeleteUserAfterDelete(
            $user
        ));

        $this->logger->info(
            'The user was deleted',
        );

        return new Payload(Payload::STATUS_DELETED);
    }

    private function deleteUserSessions(UserEntity $user): void
    {
        $this->entityManager->createQueryBuilder()
            ->delete(UserSessionRecord::class, 'us')
            ->where('us.userId = :userId')
            ->setParameter('userId', $user->id())
            ->getQuery()
            ->execute();
    }

    private function deletePasswordResetTokens(UserEntity $user): void
    {
        $this->entityManager->createQueryBuilder()
            ->delete(UserPasswordResetTokenRecord::class, 't')
            ->where('t.userId = :userId')
            ->setParameter('userId', $user->id())
            ->getQuery()
            ->execute();
    }

    private function deleteUser(UserEntity $user): void
    {
        $this->entityManager->createQueryBuilder()
            ->delete(UserRecord::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $user->id())
            ->getQuery()
            ->execute();
    }
}
