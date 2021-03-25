<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait RunOrder
{
    private int $runOrder;

    public function runOrder(): int
    {
        return $this->runOrder;
    }

    /**
     * @return $this
     */
    public function withRunOrder(int $runOrder): self
    {
        $clone = clone $this;

        $clone->runOrder = $runOrder;

        return $clone;
    }
}
