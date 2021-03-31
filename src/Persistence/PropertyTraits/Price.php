<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Price
{
    /**
     * @Mapping\Column(
     *     name="price",
     *     type="integer",
     * )
     */
    protected int $price = 0;

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}
