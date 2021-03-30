<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserCollection;
use App\Persistence\Entities\Users\UserRecord;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class FetchUsers
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function fetch(UserQueryBuilder $queryBuilder): UserCollection
    {
        try {
            return $this->innerFetch($queryBuilder);
        } catch (Throwable $e) {
            if ($this->config->devMode()) {
                throw $e;
            }

            $this->logger->emergency(
                'An exception was caught querying for users',
                ['exception' => $e],
            );

            return new UserCollection();
        }
    }

    /** @phpstan-ignore-next-line */
    private function innerFetch(UserQueryBuilder $queryBuilder): UserCollection
    {
        /** @psalm-suppress MixedArgument */
        return new UserCollection(array_map(
            static fn (UserRecord $r) => User::fromRecord(
                $r
            ),
            $queryBuilder->createQuery(
                $this->entityManager
            )->getResult(),
        ));
    }
}
