<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait PhpVersion
{
    /**
     * @Mapping\Column(
     *     name="php_version",
     *     type="string",
     * )
     */
    protected string $phpVersion = '';

    public function getPhpVersion(): string
    {
        return $this->phpVersion;
    }

    public function setPhpVersion(string $phpVersion): void
    {
        $this->phpVersion = $phpVersion;
    }
}
