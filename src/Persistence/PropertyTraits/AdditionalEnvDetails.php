<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait AdditionalEnvDetails
{
    /**
     * @Mapping\Column(
     *     name="additional_env_details",
     *     type="text",
     * )
     */
    protected string $additionalEnvDetails = '';

    public function getAdditionalEnvDetails(): string
    {
        return $this->additionalEnvDetails;
    }

    public function setAdditionalEnvDetails(string $additionalEnvDetails): void
    {
        $this->additionalEnvDetails = $additionalEnvDetails;
    }
}
