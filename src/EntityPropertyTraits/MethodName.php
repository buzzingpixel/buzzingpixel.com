<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait MethodName
{
    private string $methodName;

    public function methodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return $this
     */
    public function withMethodName(string $methodName): self
    {
        $clone = clone $this;

        $clone->methodName = $methodName;

        return $clone;
    }
}
