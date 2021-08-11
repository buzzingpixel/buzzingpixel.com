<?php

declare(strict_types=1);

namespace App\Context\Licenses\Factories;

use App\Context\Licenses\Contracts\SaveLicense;
use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Services\SaveLicenseExisting;
use App\Context\Licenses\Services\SaveLicenseInvalid;
use App\Context\Licenses\Services\SaveLicenseNew;
use App\Persistence\Entities\Licenses\LicenseRecord;

class SaveLicenseFactory
{
    public function __construct(
        private SaveLicenseNew $saveLicenseNew,
        private SaveLicenseInvalid $saveLicenseInvalid,
        private SaveLicenseExisting $saveLicenseExisting,
        private LicenseValidityFactory $licenseValidityFactory,
    ) {
    }

    public function createSaveLicense(
        License $license,
        ?LicenseRecord $licenseRecord = null,
    ): SaveLicense {
        $validity = $this->licenseValidityFactory->createLicenseValidity(
            license: $license
        );

        if (! $validity->isValid()) {
            return $this->saveLicenseInvalid->withValidity(validity: $validity);
        }

        if ($licenseRecord === null) {
            return $this->saveLicenseNew;
        }

        return $this->saveLicenseExisting;
    }
}
