<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait MaxVersion
{
    /**
     * @Mapping\Column(
     *     name="max_version",
     *     type="string",
     *     options={"default" : ""},
     * )
     */
    protected string $maxVersion = '';

    public function getMaxVersion(): string
    {
        return $this->maxVersion;
    }

    public function setMaxVersion(string $maxVersion): void
    {
        $this->maxVersion = $maxVersion;
    }
}
