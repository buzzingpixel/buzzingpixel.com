<?php

declare(strict_types=1);

namespace App\Context\Licenses\Factories;

use App\Context\Licenses\Contracts\SaveLicense;
use App\Context\Licenses\Services\SaveLicenseExisting;
use App\Context\Licenses\Services\SaveLicenseNew;
use App\Persistence\Entities\Licenses\LicenseRecord;
use Psr\Log\LoggerInterface;

class SaveLicenseFactory
{
    public function __construct(
        private LoggerInterface $logger,
        private SaveLicenseNew $saveLicenseNew,
        private SaveLicenseExisting $saveLicenseExisting,
    ) {
    }

    public function createSaveLicense(
        ?LicenseRecord $licenseRecord = null,
    ): SaveLicense {
        if ($licenseRecord === null) {
            return $this->saveLicenseNew;
        }

        return $this->saveLicenseExisting;
    }
}
