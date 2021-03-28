<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsForSale
{
    /**
     * @Mapping\Column(
     *     name="is_for_sale",
     *     type="boolean",
     * )
     */
    protected bool $isForSale = false;

    public function getIsForSale(): bool
    {
        return $this->isForSale;
    }

    public function setIsForSale(bool $isForSale): void
    {
        $this->isForSale = $isForSale;
    }
}
