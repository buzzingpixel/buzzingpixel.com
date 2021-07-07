<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Context\Licenses\Contracts\SaveLicense;
use App\Context\Licenses\Entities\License;
use App\Payload\Payload;

class SaveLicenseExisting implements SaveLicense
{
    public function save(License $license): Payload
    {
        // TODO: Implement save() method.
    }
}
