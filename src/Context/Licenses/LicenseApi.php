<?php

declare(strict_types=1);

namespace App\Context\Licenses;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Services\SaveLicense;
use App\Payload\Payload;

class LicenseApi
{
    public function __construct(
        private SaveLicense $saveLicense,
    ) {
    }

    public function saveLicense(License $license): Payload
    {
        return $this->saveLicense->save($license);
    }
}
