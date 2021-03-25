<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait ClassName
{
    private string $className;

    public function className(): string
    {
        return $this->className;
    }

    /**
     * @return $this
     */
    public function withClassName(string $className): self
    {
        $clone = clone $this;

        $clone->className = $className;

        return $clone;
    }
}
