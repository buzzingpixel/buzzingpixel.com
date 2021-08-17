<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait PrivateInfo
{
    private string $privateInfo;

    public function privateInfo(): string
    {
        return $this->privateInfo;
    }

    /**
     * @return $this
     */
    public function withPrivateInfo(string $privateInfo): self
    {
        $clone = clone $this;

        $clone->privateInfo = $privateInfo;

        return $clone;
    }
}
