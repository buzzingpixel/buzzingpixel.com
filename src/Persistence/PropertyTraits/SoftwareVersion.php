<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait SoftwareVersion
{
    /**
     * @Mapping\Column(
     *     name="software_version",
     *     type="string",
     * )
     */
    protected string $softwareVersion = '';

    public function getSoftwareVersion(): string
    {
        return $this->softwareVersion;
    }

    public function setSoftwareVersion(string $softwareVersion): void
    {
        $this->softwareVersion = $softwareVersion;
    }
}
