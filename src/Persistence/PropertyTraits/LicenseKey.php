<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait LicenseKey
{
    /**
     * @Mapping\Column(
     *     name="license_key",
     *     type="string",
     * )
     */
    protected string $licenseKey = '';

    public function getLicenseKey(): string
    {
        return $this->licenseKey;
    }

    public function setLicenseKey(string $licenseKey): void
    {
        $this->licenseKey = $licenseKey;
    }
}
