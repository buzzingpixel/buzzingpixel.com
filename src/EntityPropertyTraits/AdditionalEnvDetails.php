<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait AdditionalEnvDetails
{
    private string $additionalEnvDetails;

    public function additionalEnvDetails(): string
    {
        return $this->additionalEnvDetails;
    }

    /**
     * @return $this
     */
    public function withAdditionalEnvDetails(string $additionalEnvDetails): self
    {
        $clone = clone $this;

        $clone->additionalEnvDetails = $additionalEnvDetails;

        return $clone;
    }
}
