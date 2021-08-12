<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\GetOrCreateUser;

use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

use function filter_var;

use const FILTER_VALIDATE_EMAIL;

class GetOrCreateUserFactory
{
    public function __construct(
        private UserApi $userApi,
        private GetOrCreateUserNew $getOrCreateUserNew,
    ) {
    }

    public function create(string $userEmailAddress): GetOrCreateUserContract
    {
        if (
            $userEmailAddress === '' ||
            filter_var(
                $userEmailAddress,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {
            return new GetOrCreateUserNull();
        }

        $user = $this->userApi->fetchOneUser(
            queryBuilder: (new UserQueryBuilder())->withEmailAddress(
                value: $userEmailAddress
            ),
        );

        if ($user !== null) {
            return new GetOrCreateUserExisting($user);
        }

        return $this->getOrCreateUserNew;
    }
}
