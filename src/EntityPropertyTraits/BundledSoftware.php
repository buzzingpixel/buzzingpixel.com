<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use function count;

trait BundledSoftware
{
    /** @var string[] */
    private array $bundledSoftware;

    /**
     * @return string[]
     */
    public function bundledSoftware(): array
    {
        return $this->bundledSoftware;
    }

    /**
     * @param string[] $bundledSoftware
     */
    public function withBundledSoftware(array $bundledSoftware): self
    {
        $clone = clone $this;

        $clone->bundledSoftware = $bundledSoftware;

        return $clone;
    }

    public function isBundle(): bool
    {
        return count($this->bundledSoftware) > 0;
    }

    public function isNotBundle(): bool
    {
        return ! $this->isBundle();
    }
}
