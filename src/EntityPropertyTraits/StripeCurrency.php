<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait StripeCurrency
{
    private string $stripeCurrency;

    public function stripeCurrency(): string
    {
        return $this->stripeCurrency;
    }

    /**
     * @return $this
     */
    public function withStripeCurrency(
        string $stripeCurrency
    ): self {
        $clone = clone $this;

        $clone->stripeCurrency = $stripeCurrency;

        return $clone;
    }
}
