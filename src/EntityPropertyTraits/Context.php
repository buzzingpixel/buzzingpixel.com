<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait Context
{
    /** @var mixed[] */
    private array $context;

    /**
     * @return mixed[]
     */
    public function context(): array
    {
        return $this->context;
    }

    /**
     * @param mixed[] $context
     */
    public function withContext(array $context): self
    {
        $clone = clone $this;

        $clone->context = $context;

        return $clone;
    }
}
