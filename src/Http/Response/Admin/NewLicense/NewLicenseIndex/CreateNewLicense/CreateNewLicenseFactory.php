<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\CreateNewLicense;

use App\Context\Software\Entities\Software;
use App\Context\Users\Entities\User;

class CreateNewLicenseFactory
{
    public function __construct(
        private CreateNewLicense $createNewLicense,
        private CreateNewLicenseInvalid $createNewLicenseNoOp,
    ) {
    }

    public function create(
        ?User $user,
        ?Software $software,
    ): CreateNewLicenseContract {
        if ($user === null || $software === null) {
            return $this->createNewLicenseNoOp;
        }

        return $this->createNewLicense;
    }
}
