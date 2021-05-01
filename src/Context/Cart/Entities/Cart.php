<?php

declare(strict_types=1);

namespace App\Context\Cart\Entities;

use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\CreatedAt;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\LastTouchedAt;
use App\EntityPropertyTraits\User;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Cart\CartItemRecord;
use App\Persistence\Entities\Cart\CartRecord;
use App\Utilities\DateTimeUtility;
use DateTimeInterface;
use LogicException;
use Ramsey\Uuid\UuidInterface;

use function array_map;
use function array_merge;
use function is_array;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class Cart
{
    use Id;
    use LastTouchedAt;
    use CreatedAt;
    use User;

    /** @phpstan-ignore-next-line  */
    private CartItemCollection $cartItems;

    public static function fromRecord(CartRecord $record): self
    {
        /** @psalm-suppress PossiblyNullArgument */
        return (new self(
            id: $record->getId(),
            lastTouchedAt: $record->getLastTouchedAt(),
            createdAt: $record->getCreatedAt(),
            user: $record->getUser() !== null ? UserEntity::fromRecord(
                $record->getUser()
            ) :
            null,
        ))->withItemsFromRecord($record);
    }

    /** @phpstan-ignore-next-line  */
    public function __construct(
        ?UserEntity $user = null,
        null | array | CartItemCollection $cartItems = [],
        null | string | DateTimeInterface $lastTouchedAt = null,
        null | string | DateTimeInterface $createdAt = null,
        null | string | UuidInterface $id = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->user = $user;

        if ($cartItems instanceof CartItemCollection) {
            $cartItems = $cartItems->toArray();
        } elseif (! is_array($cartItems)) {
            $cartItems = [];
        }

        $this->cartItems = new CartItemCollection(array_map(
            fn (CartItem $i) => $i->withCart(
                $this,
            ),
            array_merge($cartItems)
        ));

        $this->lastTouchedAt = DateTimeUtility::createDateTimeImmutable(
            $lastTouchedAt,
        );

        $this->createdAt = DateTimeUtility::createDateTimeImmutable(
            $createdAt,
        );
    }

    private bool $isInitialized = false;

    /** @phpstan-ignore-next-line */
    public function cartItems(): CartItemCollection
    {
        return $this->cartItems;
    }

    /** @phpstan-ignore-next-line  */
    public function withCartItems(array | CartItemCollection $cartItems): self
    {
        $clone = clone $this;

        if (! is_array($cartItems)) {
            $cartItems = $cartItems->toArray();
        }

        $clone->cartItems = new CartItemCollection(array_map(
            static fn (CartItem $i) => $i->withCart(
                $clone
            ),
            array_merge($cartItems)
        ));

        return $clone;
    }

    public function withAddedCartItem(CartItem $newCartItem): self
    {
        $clone = clone $this;

        $clone->cartItems = new CartItemCollection(array_map(
            static fn (CartItem $i) => $i->withCart(
                $clone
            ),
            array_merge(
                $this->cartItems->toArray(),
                [$newCartItem]
            ),
        ));

        return $clone;
    }

    public function withItemsFromRecord(CartRecord $record): self
    {
        $clone = clone $this;

        $clone->cartItems = new CartItemCollection(array_map(
            static fn (
                CartItemRecord $r
            ) => CartItem::fromRecord(
                $r,
                $clone,
            ),
            $record->getCartItems()->toArray(),
        ));

        return $clone;
    }
}
