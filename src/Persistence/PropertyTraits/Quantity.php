<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Quantity
{
    /**
     * @Mapping\Column(
     *     name="quantity",
     *     type="integer",
     * )
     */
    protected int $quantity = 0;

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
