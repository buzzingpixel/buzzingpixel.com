<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Handle
{
    /**
     * @Mapping\Column(
     *     name="handle",
     *     type="string",
     * )
     */
    protected string $handle = '';

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function setHandle(string $handle): void
    {
        $this->handle = $handle;
    }
}
