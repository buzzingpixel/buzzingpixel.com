<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait SubTotal
{
    /**
     * @Mapping\Column(
     *     name="sub_total",
     *     type="integer",
     * )
     */
    protected int $subTotal = 0;

    public function getSubTotal(): int
    {
        return $this->subTotal;
    }

    public function setSubTotal(int $subTotal): void
    {
        $this->subTotal = $subTotal;
    }
}
