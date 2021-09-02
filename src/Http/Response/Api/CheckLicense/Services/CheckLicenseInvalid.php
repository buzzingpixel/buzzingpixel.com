<?php

declare(strict_types=1);

namespace App\Http\Response\Api\CheckLicense\Services;

use App\Http\Response\Api\CheckLicense\Contracts\CheckLicenseContract;
use App\Http\Response\Api\CheckLicense\Entities\PostValues;
use App\Http\Response\Api\CheckLicense\Entities\Response;

class CheckLicenseInvalid implements CheckLicenseContract
{
    public function check(PostValues $values): Response
    {
        return new Response(
            message: 'invalid',
            reason: 'Malformed request',
        );
    }
}
