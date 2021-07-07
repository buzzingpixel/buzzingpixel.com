<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use App\Context\Software\Entities\Software as SoftwareEntity;

trait Software
{
    private ?SoftwareEntity $software;

    public function software(): ?SoftwareEntity
    {
        return $this->software;
    }

    /**
     * @return $this
     */
    public function withSoftware(?SoftwareEntity $software): self
    {
        $clone = clone $this;

        $clone->software = $software;

        return $clone;
    }
}
