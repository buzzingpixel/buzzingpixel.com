<?php

declare(strict_types=1);

namespace App\Context\Cart\Services;

use App\Context\Cart\Entities\Cart;
use App\Context\Cart\Entities\CartItem;
use App\Payload\Payload;
use App\Persistence\Entities\Cart\CartItemRecord;
use App\Persistence\Entities\Cart\CartRecord;
use App\Persistence\Entities\Software\SoftwareRecord;
use Config\General;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;
use function assert;

class SaveCart
{
    public function __construct(
        private EntityManager $entityManager,
        private LoggerInterface $logger,
        private General $config,
    ) {
    }

    public function save(Cart $cart): Payload
    {
        try {
            return $this->innerSave($cart);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught saving a cart',
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
    private function innerSave(Cart $cart): Payload
    {
        $cart = $cart->withLastTouchedAt(new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC')
        ));

        $payloadStatus = Payload::STATUS_UPDATED;

        $this->logger->info(
            'Checking for existing cart by ID: ' . $cart->id()
        );

        $record = $this->entityManager->find(
            CartRecord::class,
            $cart->id(),
        );

        if ($record === null) {
            $payloadStatus = Payload::STATUS_CREATED;

            $this->logger->info(
                'Creating new cart record',
            );

            $record = new CartRecord();
        }

        $record->hydrateFromEntity(
            $cart,
            $this->entityManager
        );

        foreach ($cart->removedCartItemIds() as $cartItemId) {
            foreach ($record->getCartItems()->toArray() as $cartItemRecord) {
                /**
                 * Psalm needs the assert for... stupid reasons... who knows
                 *
                 * @phpstan-ignore-next-line
                 */
                assert($cartItemRecord instanceof CartItemRecord);

                if ($cartItemRecord->getId()->toString() !== $cartItemId) {
                    continue;
                }

                $this->entityManager->remove($cartItemRecord);
            }
        }

        $record->setCartItems(new ArrayCollection(
            array_map(
                function (CartItem $item) use (
                    $record
                ): CartItemRecord {
                    $itemRecord = $this->entityManager->find(
                        CartItemRecord::class,
                        $item->id(),
                    );

                    if ($itemRecord === null) {
                        $itemRecord = new CartItemRecord();
                    }

                    $itemRecord->hydrateFromEntity($item);

                    $itemRecord->setCart($record);

                    $softwareRecord = $this->entityManager->find(
                        SoftwareRecord::class,
                        $item->software()->id(),
                    );

                    assert($softwareRecord instanceof SoftwareRecord);

                    $itemRecord->setSoftware($softwareRecord);

                    return $itemRecord;
                },
                $cart->cartItems()->toArray()
            ),
        ));

        $this->entityManager->persist($record);

        $this->entityManager->flush();

        return new Payload(
            $payloadStatus,
            ['cartEntity' => $cart],
        );
    }
}
