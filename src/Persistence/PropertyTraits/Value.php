<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

use function serialize;
use function unserialize;

trait Value
{
    /**
     * @Mapping\Column(
     *     name="value",
     *     type="text",
     * )
     */
    protected string $value = '';

    public function getValue(): mixed
    {
        return unserialize($this->value);
    }

    public function setValue(mixed $value): void
    {
        $this->value = serialize($value);
    }
}
