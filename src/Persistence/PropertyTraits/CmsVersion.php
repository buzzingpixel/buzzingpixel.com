<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait CmsVersion
{
    /**
     * @Mapping\Column(
     *     name="cms_version",
     *     type="string",
     * )
     */
    protected string $cmsVersion = '';

    public function getCmsVersion(): string
    {
        return $this->cmsVersion;
    }

    public function setCmsVersion(string $cmsVersion): void
    {
        $this->cmsVersion = $cmsVersion;
    }
}
