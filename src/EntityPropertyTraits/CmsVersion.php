<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait CmsVersion
{
    private string $cmsVersion;

    public function cmsVersion(): string
    {
        return $this->cmsVersion;
    }

    /**
     * @return $this
     */
    public function withCmsVersion(string $cmsVersion): self
    {
        $clone = clone $this;

        $clone->cmsVersion = $cmsVersion;

        return $clone;
    }
}
