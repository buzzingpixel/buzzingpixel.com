<?php

declare(strict_types=1);

namespace App\Context\Orders\Services;

use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderCollection;
use App\Persistence\Entities\Orders\OrderRecord;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class FetchOrders
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function fetch(
        OrderQueryBuilder $queryBuilder,
    ): OrderCollection {
        try {
            return $this->innerFetch($queryBuilder);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                /** @noinspection PhpUnhandledExceptionInspection */
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for Licenses',
                ['exception' => $exception],
            );

            return new OrderCollection();
        }
    }

    /** @phpstan-ignore-next-line */
    private function innerFetch(
        OrderQueryBuilder $queryBuilder,
    ): OrderCollection {
        /** @psalm-suppress MixedArgument */
        return new OrderCollection(array_map(
            static fn (OrderRecord $o) => Order::fromRecord(
                $o
            ),
            $queryBuilder->createQuery(
                $this->entityManager
            )->getResult(),
        ));
    }
}
