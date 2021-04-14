<?php

declare(strict_types=1);

namespace App\Persistence\QueryBuilders;

class SearchField
{
    public function __construct(
        private string $property,
        private string $value,
    ) {
    }

    public function property(): string
    {
        return $this->property;
    }

    public function value(): string
    {
        return $this->value;
    }
}
