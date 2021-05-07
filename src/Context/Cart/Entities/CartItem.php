<?php

declare(strict_types=1);

namespace App\Context\Cart\Entities;

use App\Context\Software\Entities\Software;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\Quantity;
use App\EntityPropertyTraits\Slug;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Cart\CartItemRecord;
use LogicException;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class CartItem
{
    use Id;
    use Quantity;
    use Slug;

    private Cart $cart;
    private Software $software;

    public static function fromRecord(
        CartItemRecord $record,
        Cart $cart,
    ): self {
        $softwareRecord = $record->getSoftware();

        $software = Software::fromRecord($softwareRecord);

        return new self(
            id: $record->getId(),
            quantity: $record->getQuantity(),
            slug: $record->getSlug(),
            cart: $cart,
            software: $software,
        );
    }

    public function __construct(
        Software $software,
        Cart $cart = null,
        int $quantity = 0,
        string $slug = '',
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

        if ($cart !== null) {
            $this->cart = $cart;
        }

        $this->software = $software;

        $this->quantity = $quantity;

        $this->slug = $software->slug();

        if ($slug !== '') {
            $this->slug = $slug;
        }

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function cart(): Cart
    {
        return $this->cart;
    }

    /**
     * @return $this
     */
    public function withCart(Cart $cart): self
    {
        $clone = clone $this;

        $clone->cart = $cart;

        return $clone;
    }

    public function software(): Software
    {
        return $this->software;
    }

    /**
     * @return $this
     */
    public function withSoftware(Software $software): self
    {
        $clone = clone $this;

        $clone->software = $software;

        return $clone;
    }
}
