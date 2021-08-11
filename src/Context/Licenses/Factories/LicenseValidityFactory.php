<?php

declare(strict_types=1);

namespace App\Context\Licenses\Factories;

use App\Context\Licenses\Contracts\LicenseValidity;
use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Entities\LicenseValidityIsInvalid;
use App\Context\Licenses\Entities\LicenseValidityIsValid;

use function count;

class LicenseValidityFactory
{
    public function createLicenseValidity(License $license): LicenseValidity
    {
        $validationErrors = [];

        if ($license->majorVersion() === '') {
            $validationErrors[] = 'Major version must be specified';
        }

        if ($license->licenseKey() === '') {
            $validationErrors[] = 'License key must be specified';
        }

        if ($license->user() === null) {
            $validationErrors[] = 'User must be specified';
        }

        if ($license->software() === null) {
            $validationErrors[] = 'Software must be specified';
        }

        if ($license->hasExceededMaxDomains()) {
            $validationErrors[] = 'Only ' . License::MAX_AUTHORIZED_DOMAINS .
                ' authorized domains may be added.';
        }

        if (count($validationErrors) > 0) {
            return new LicenseValidityIsInvalid(
                validationErrors: $validationErrors,
            );
        }

        return new LicenseValidityIsValid();
    }
}
