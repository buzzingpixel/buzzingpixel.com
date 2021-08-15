<?php

declare(strict_types=1);

namespace App\Context\Path\Entities;

class Path
{
    public function __construct(private string $path)
    {
    }

    public function __toString(): string
    {
        return $this->getPath();
    }

    public function toString(): string
    {
        return $this->getPath();
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
