<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

class SearchParams
{
    public function __construct(
        private string $search = '',
        private int $limit = 20,
        private int $offset = 0,
    ) {
    }

    public function search(): string
    {
        return $this->search;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }
}
