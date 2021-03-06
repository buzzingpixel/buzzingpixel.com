<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders;

class OrderBy
{
    public function __construct(
        private string $column,
        private string $direction = 'ASC',
    ) {
    }

    public function column(): string
    {
        return $this->column;
    }

    public function direction(): string
    {
        return $this->direction;
    }
}
