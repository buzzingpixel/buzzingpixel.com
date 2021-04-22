<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait OrderDate
{
    /**
     * @Mapping\Column(
     *     name="order_date",
     *     type="datetimetz_immutable",
     *     nullable=true
     * )
     */
    protected ?DateTimeImmutable $orderDate;

    public function getOrderDate(): ?DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function setOrderDate(?DateTimeImmutable $orderDate): void
    {
        $this->orderDate = $orderDate;
    }
}
