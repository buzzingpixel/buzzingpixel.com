<?php

declare(strict_types=1);

namespace App\Context\Orders\Entities;

use App\Context\Licenses\Entities\License;
use App\Context\Software\Entities\Software;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\OriginalPrice;
use App\EntityPropertyTraits\Price;
use App\EntityPropertyTraits\Quantity;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Orders\OrderItemRecord;
use LogicException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class OrderItem
{
    use Id;
    use Price;
    use OriginalPrice;
    use Quantity;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private Order $order;
    /** @psalm-suppress PropertyNotSetInConstructor */
    private License $license;
    /** @psalm-suppress PropertyNotSetInConstructor */
    private Software $software;

    public static function fromRecord(
        OrderItemRecord $record,
        ?Order $order = null,
    ): self {
        $orderItem = new self(
            id: $record->getId(),
            price: $record->getPrice(),
            originalPrice: $record->getOriginalPrice(),
            quantity: $record->getQuantity(),
            order: $order,
            software: Software::fromRecord($record->getSoftware()),
        );

        $license = $record->getLicense();

        if ($license !== null) {
            $orderItem = $orderItem->withLicense(
                License::fromRecord($license),
            );
        }

        return $orderItem;
    }

    public function __construct(
        int | Money $price = 0,
        int | Money $originalPrice = 0,
        int $quantity = 1,
        ?Order $order = null,
        ?License $license = null,
        ?Software $software = null,
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

        if ($price instanceof Money) {
            $this->price = $price;
        } else {
            $this->price = new Money(
                $price,
                new Currency('USD')
            );
        }

        if ($originalPrice instanceof Money) {
            $this->originalPrice = $originalPrice;
        } else {
            $this->originalPrice = new Money(
                $originalPrice,
                new Currency('USD')
            );
        }

        $this->quantity = $quantity;

        if ($order !== null) {
            $this->order = $order;
        }

        if ($license !== null) {
            $this->license = $license;
        }

        if ($software !== null) {
            $this->software = $software;
        }

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function order(): Order
    {
        return $this->order;
    }

    public function withOrder(Order $order): self
    {
        $clone = clone $this;

        $clone->order = $order;

        return $clone;
    }

    public function license(): License
    {
        return $this->license;
    }

    public function hasLicense(): bool
    {
        return isset($this->license);
    }

    public function withLicense(License $license): self
    {
        $clone = clone $this;

        $clone->license = $license;

        return $clone;
    }

    public function software(): Software
    {
        return $this->software;
    }

    public function hasSoftware(): bool
    {
        return isset($this->software);
    }

    public function withSoftware(Software $software): self
    {
        $clone = clone $this;

        $clone->software = $software;

        return $clone;
    }
}
