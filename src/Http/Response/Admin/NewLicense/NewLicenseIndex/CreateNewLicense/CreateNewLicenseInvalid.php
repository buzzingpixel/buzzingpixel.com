<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\CreateNewLicense;

use App\Context\Software\Entities\Software;
use App\Context\Users\Entities\User;
use App\Payload\Payload;

class CreateNewLicenseInvalid implements CreateNewLicenseContract
{
    public function create(?User $user, ?Software $software,): Payload
    {
        return new Payload(
            Payload::STATUS_NOT_VALID,
            [],
        );
    }
}
