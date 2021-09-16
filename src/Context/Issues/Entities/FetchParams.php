<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingTraversableTypeHintSpecification

class FetchParams
{
    /**
     * @param string[] $statusFilter
     */
    public function __construct(
        private int $limit = 20,
        private int $offset = 0,
        private array $statusFilter = [],
    ) {
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @return string[]
     */
    public function statusFilter(): array
    {
        return $this->statusFilter;
    }
}
