<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait MajorVersion
{
    /**
     * @Mapping\Column(
     *     name="major_version",
     *     type="string",
     * )
     */
    protected string $majorVersion = '';

    public function getMajorVersion(): string
    {
        return $this->majorVersion;
    }

    public function setMajorVersion(string $majorVersion): void
    {
        $this->majorVersion = $majorVersion;
    }
}
