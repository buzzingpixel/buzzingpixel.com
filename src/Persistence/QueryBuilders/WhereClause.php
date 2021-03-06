<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders;

class WhereClause
{
    public function __construct(
        private string $property,
        private mixed $value,
        private string $comparison = '=',
        private string $concat = 'AND',
    ) {
    }

    public function property(): string
    {
        return $this->property;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function comparison(): string
    {
        return $this->comparison;
    }

    public function concat(): string
    {
        return $this->concat;
    }
}
