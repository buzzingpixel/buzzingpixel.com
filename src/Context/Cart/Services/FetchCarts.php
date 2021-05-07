<?php

declare(strict_types=1);

namespace App\Context\Cart\Services;

use App\Context\Cart\Entities\Cart;
use App\Context\Cart\Entities\CartCollection;
use App\Persistence\Entities\Cart\CartRecord;
use App\Persistence\QueryBuilders\Cart\CartQueryBuilder;
use Config\General;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class FetchCarts
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    /**
     * @return CartCollection<Cart>
     *
     * @psalm-suppress MixedReturnTypeCoercion
     * @psalm-suppress TooManyTemplateParams
     */
    public function fetch(CartQueryBuilder $queryBuilder): CartCollection
    {
        try {
            return $this->innerFetch($queryBuilder);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught querying for software',
                ['exception' => $exception],
            );

            return new CartCollection();
        }
    }

    /**
     * @return CartCollection<Cart>
     *
     * @psalm-suppress MixedReturnTypeCoercion
     * @psalm-suppress TooManyTemplateParams
     */
    public function innerFetch(CartQueryBuilder $queryBuilder): CartCollection
    {
        /** @psalm-suppress MixedArgument */
        return new CartCollection(array_map(
            static fn (CartRecord $r) => Cart::fromRecord($r),
            $queryBuilder->createQuery(
                $this->entityManager,
            )->getResult(),
        ));
    }
}
