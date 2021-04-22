<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Tax
{
    /**
     * @Mapping\Column(
     *     name="tax",
     *     type="integer",
     * )
     */
    protected int $tax = 0;

    public function getTax(): int
    {
        return $this->tax;
    }

    public function setTax(int $tax): void
    {
        $this->tax = $tax;
    }
}
