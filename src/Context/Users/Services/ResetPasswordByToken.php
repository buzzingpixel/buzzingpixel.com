<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Payload\Payload;
use App\Persistence\Entities\Users\UserPasswordResetTokenRecord;
use Config\General;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

use function password_hash;

use const PASSWORD_DEFAULT;

class ResetPasswordByToken
{
    public function __construct(
        private FetchUserByResetToken $fetchUserByResetToken,
        private SaveUser $saveUser,
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function reset(string $token, string $newPassword): Payload
    {
        try {
            return $this->innerReset($token, $newPassword);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for users',
                ['exception' => $exception],
            );

            return new Payload(
                Payload::STATUS_NOT_VALID,
                [
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }

    public function innerReset(string $token, string $newPassword): Payload
    {
        $user = $this->fetchUserByResetToken->fetch($token);

        if ($user === null) {
            return new Payload(Payload::STATUS_NOT_VALID);
        }

        $user = $user->withPasswordHash(
            /** @phpstan-ignore-next-line */
            (string) password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            ),
        );

        $savePayload = $this->saveUser->save($user);

        if ($savePayload->getStatus() !== Payload::STATUS_UPDATED) {
            throw new Exception(
                'Unable to save user\'s new password'
            );
        }

        $this->entityManager->createQueryBuilder()
            ->delete(UserPasswordResetTokenRecord::class, 't')
            ->where('t.id = :id')
            ->setParameter('id', $token)
            ->getQuery()
            ->execute();

        return new Payload(Payload::STATUS_UPDATED);
    }
}
