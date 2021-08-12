<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\GetOrCreateUser;

use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use Ramsey\Uuid\UuidFactory;

use function assert;

class GetOrCreateUserNew implements GetOrCreateUserContract
{
    public function __construct(
        private UserApi $userApi,
        private UuidFactory $uuidFactory,
    ) {
    }

    public function getOrCreate(string $userEmailAddress): ?User
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = new User(
            emailAddress: $userEmailAddress,
            plainTextPassword: $this->uuidFactory->uuid4()->toString() .
                'TeMpP@ss2',
        );

        $savedUser = $this->userApi->saveUser(
            $user
        )->getResult()['userEntity'] ?? null;

        assert($savedUser instanceof User || $savedUser === null);

        return $savedUser;
    }
}
