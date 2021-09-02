<?php

declare(strict_types=1);

namespace App\Http\Response\Api\CheckLicense\Contracts;

use App\Http\Response\Api\CheckLicense\Entities\PostValues;
use App\Http\Response\Api\CheckLicense\Entities\Response;

interface CheckLicenseContract
{
    public function check(PostValues $values): Response;
}
