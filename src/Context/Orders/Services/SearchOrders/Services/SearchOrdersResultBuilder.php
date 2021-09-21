<?php

declare(strict_types=1);

namespace App\Context\Orders\Services\SearchOrders\Services;

use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderCollection;
use App\Context\Orders\Entities\OrderResult;
use App\Context\Orders\Entities\SearchParams;
use App\Context\Orders\Services\SearchOrders\Contracts\SearchOrdersResultBuilderContract;
use App\Persistence\Entities\Orders\OrderRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

use function array_map;

class SearchOrdersResultBuilder implements SearchOrdersResultBuilderContract
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     *
     * @inheritDoc
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): OrderResult {
        $absoluteTotal = (int) $this->entityManager
            ->getRepository(OrderRecord::class)
            ->createQueryBuilder('o')
            ->where('o.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();

        /** @var OrderRecord[] $records */
        $records = $this->entityManager
            ->getRepository(OrderRecord::class)
            ->createQueryBuilder('o')
            ->where('o.id IN (:ids)')
            ->setParameter('ids', $resultIds)
            ->setMaxResults($searchParams->limit())
            ->setFirstResult($searchParams->offset())
            ->getQuery()
            ->getResult();

        $intermediateCollection = new OrderCollection(
            array_map(
                static fn (OrderRecord $r) => Order::fromRecord(
                    record: $r,
                ),
                $records
            ),
        );

        $finalCollection = new OrderCollection();

        foreach ($resultIds as $id) {
            $collection = $intermediateCollection->filter(
                static fn (Order $o) => $o->id() === $id,
            );

            if ($collection->count() < 1) {
                continue;
            }

            $finalCollection->add($collection->first());
        }

        return new OrderResult(
            absoluteTotal: $absoluteTotal,
            orders: $finalCollection,
        );
    }
}
