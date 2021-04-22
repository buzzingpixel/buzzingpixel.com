<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Total
{
    /**
     * @Mapping\Column(
     *     name="total",
     *     type="integer",
     * )
     */
    protected int $total = 0;

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}
