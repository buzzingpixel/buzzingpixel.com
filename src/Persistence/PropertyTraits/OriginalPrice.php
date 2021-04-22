<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait OriginalPrice
{
    /**
     * @Mapping\Column(
     *     name="original_price",
     *     type="integer",
     * )
     */
    protected int $originalPrice = 0;

    public function getOriginalPrice(): int
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(int $originalPrice): void
    {
        $this->originalPrice = $originalPrice;
    }
}
