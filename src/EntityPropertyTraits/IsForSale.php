<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IsForSale
{
    private bool $isForSale = false;

    public function isForSale(): bool
    {
        return $this->isForSale;
    }

    /**
     * @return $this
     */
    public function withIsForSale(bool $isForSale): self
    {
        $clone = clone $this;

        $clone->isForSale = $isForSale;

        return $clone;
    }
}
