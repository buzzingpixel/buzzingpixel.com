<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Value
{
    /**
     * @Mapping\Column(
     *     name="value",
     *     type="text",
     * )
     */
    protected string $value = '';

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
