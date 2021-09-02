<?php

declare(strict_types=1);

namespace App\Http\Response\Api\CheckLicense\Services;

use App\Context\Licenses\LicenseApi;
use App\Http\Response\Api\CheckLicense\Contracts\CheckLicenseContract;
use App\Http\Response\Api\CheckLicense\Entities\PostValues;
use App\Http\Response\Api\CheckLicense\Entities\Response;

class CheckLicense implements CheckLicenseContract
{
    public function __construct(private LicenseApi $licenseApi)
    {
    }

    public function check(PostValues $values): Response
    {
        $result = $this->licenseApi->checkLicenseStatus(
            slug: $values->app()->toString(),
            domain: $values->domain()->toString(),
            license: $values->license()->toString(),
            version: $values->version()->toString(),
        );

        return new Response(
            message: $result->isValid() ? 'valid' : 'invalid',
            reason: $result->reason(),
        );
    }
}
