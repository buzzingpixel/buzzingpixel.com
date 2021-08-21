<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

class FetchParams
{
    public function __construct(
        private int $limit = 20,
        private int $offset = 0,
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
}
