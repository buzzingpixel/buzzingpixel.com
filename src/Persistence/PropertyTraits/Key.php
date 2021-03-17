<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Key
{
    /**
     * @Mapping\Column(
     *     name="key",
     *     type="text",
     * )
     */
    protected string $key = '';

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }
}
