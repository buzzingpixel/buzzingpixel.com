<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait OldOrderNumber
{
    private string $oldOrderNumber;

    public function oldOrderNumber(): string
    {
        return $this->oldOrderNumber;
    }

    /**
     * @return $this
     */
    public function withOldOrderNumber(string $oldOrderNumber): self
    {
        $clone = clone $this;

        $clone->oldOrderNumber = $oldOrderNumber;

        return $clone;
    }
}
