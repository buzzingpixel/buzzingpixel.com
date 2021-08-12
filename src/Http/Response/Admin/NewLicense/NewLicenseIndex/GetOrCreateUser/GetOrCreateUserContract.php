<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\GetOrCreateUser;

use App\Context\Users\Entities\User;

interface GetOrCreateUserContract
{
    public function getOrCreate(string $userEmailAddress): ?User;
}
