<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\GetOrCreateUser;

use App\Context\Users\Entities\User;

class GetOrCreateUserNull implements GetOrCreateUserContract
{
    public function getOrCreate(string $userEmailAddress): ?User
    {
        return null;
    }
}
