<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Version
{
    /**
     * @Mapping\Column(
     *     name="version",
     *     type="string",
     * )
     */
    protected string $version = '';

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }
}
