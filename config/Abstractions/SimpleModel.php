<?php

declare(strict_types=1);

namespace Config\Abstractions;

class SimpleModel
{
    /**
     * @param mixed[] $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        return static::${$name} ?? null;
    }
}
