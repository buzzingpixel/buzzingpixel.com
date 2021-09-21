<?php

declare(strict_types=1);

namespace App\Context\Users\Services\SearchUsers\Services;

use App\Context\Users\Entities\SearchParams;
use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserCollection;
use App\Context\Users\Entities\UserResult;
use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\Context\Users\Services\SearchUsers\Contracts\SearchUsersResultBuilderContract;
use App\Persistence\Entities\Users\UserRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

use function array_map;

class SearchUsersResultBuilder implements SearchUsersResultBuilderContract
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    /**
     * @throws InvalidEmailAddress
     * @throws InvalidPassword
     * @throws NoResultException
     * @throws NonUniqueResultException
     *
     * @inheritDoc
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): UserResult {
        $absoluteTotal = (int) $this->entityManager
            ->getRepository(UserRecord::class)
            ->createQueryBuilder('u')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        /** @var UserRecord[] $records */
        $records = $this->entityManager
            ->getRepository(UserRecord::class)
            ->createQueryBuilder('u')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->setMaxResults($searchParams->limit())
            ->setFirstResult($searchParams->offset())
            ->getQuery()
            ->getResult();

        $intermediateCollection = new UserCollection(
            array_map(
                static fn (UserRecord $r) => User::fromRecord(
                    record: $r,
                ),
                $records,
            ),
        );

        $finalCollection = new UserCollection();

        foreach ($resultIds as $id) {
            $collection = $intermediateCollection->filter(
                static fn (User $u) => $u->id() === $id,
            );

            if ($collection->count() < 1) {
                continue;
            }

            $finalCollection->add($collection->first());
        }

        return new UserResult(
            absoluteTotal: $absoluteTotal,
            users: $finalCollection,
        );
    }
}
