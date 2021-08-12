<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\GetOrCreateUser;

use App\Context\Users\Entities\User;

class GetOrCreateUserExisting implements GetOrCreateUserContract
{
    public function __construct(private User $user)
    {
    }

    public function getOrCreate(string $userEmailAddress): ?User
    {
        return $this->user;
    }
}
