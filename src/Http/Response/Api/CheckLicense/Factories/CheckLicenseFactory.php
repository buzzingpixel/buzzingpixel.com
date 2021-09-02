<?php

declare(strict_types=1);

namespace App\Http\Response\Api\CheckLicense\Factories;

use App\Http\Response\Api\CheckLicense\Contracts\CheckLicenseContract;
use App\Http\Response\Api\CheckLicense\Entities\PostValues;
use App\Http\Response\Api\CheckLicense\Services\CheckLicense;
use App\Http\Response\Api\CheckLicense\Services\CheckLicenseInvalid;

class CheckLicenseFactory
{
    public function __construct(
        private CheckLicense $checkLicense,
        private CheckLicenseInvalid $invalid,
    ) {
    }

    public function getCheckLicense(PostValues $values): CheckLicenseContract
    {
        if ($values->isNotValid()) {
            return $this->invalid;
        }

        return $this->checkLicense;
    }
}
