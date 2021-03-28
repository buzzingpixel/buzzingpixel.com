<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Price
{
    /**
     * @Mapping\Column(
     *     name="price",
     *     type="float",
     * )
     */
    protected float | int $price = 0.0;

    public function getPrice(): float | int
    {
        return $this->price;
    }

    public function setPrice(float | int $price): void
    {
        $this->price = $price;
    }
}
