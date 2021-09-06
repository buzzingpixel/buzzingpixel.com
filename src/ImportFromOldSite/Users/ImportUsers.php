<?php

declare(strict_types=1);

namespace App\ImportFromOldSite\Users;

use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserBillingProfile;
use App\Context\Users\Entities\UserSupportProfile;
use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\Context\Users\UserApi;
use Config\General;
use DateTimeImmutable;
use DateTimeInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use function array_map;
use function assert;
use function json_decode;

class ImportUsers
{
    public function __construct(
        private Client $guzzle,
        private General $config,
        private UserApi $userApi,
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function importUser(): void
    {
        $request = $this->guzzle->get(
            $this->config->oldSiteUrl('/new-site-transfer/users'),
            [
                'query' => ['key' => $this->config->oldSiteTransferKey()],
            ],
        );

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedArrayAccess
         */
        array_map(
            [$this, 'importUserItem'],
            json_decode((string) $request->getBody(), true)
        );
    }

    /**
     * @param mixed[] $userData
     *
     * @throws InvalidEmailAddress
     * @throws InvalidPassword
     */
    protected function importUserItem(array $userData): void
    {
        $createdAt = DateTimeImmutable::createFromFormat(
            DateTimeInterface::ATOM,
            (string) $userData['createdAt'],
        );

        assert($createdAt instanceof DateTimeInterface);

        /**
         * @psalm-suppress MixedArrayAccess
         */
        $user = new User(
            emailAddress: (string) $userData['emailAddress'],
            passwordHash: (string) $userData['passwordHash'],
            createdAt: $createdAt,
            supportProfile: new UserSupportProfile(
                displayName: (string) $userData['supportProfile']['displayName'],
            ),
            billingProfile: new UserBillingProfile(
                billingName: (string) $userData['billingProfile']['billingName'],
                billingCompany: (string) $userData['billingProfile']['billingCompany'],
                billingPhone: (string) $userData['billingProfile']['billingPhone'],
                billingCountryRegion: (string) $userData['billingProfile']['billingCountryRegion'],
                billingAddress: (string) $userData['billingProfile']['billingAddress'],
                billingAddressContinued: (string) $userData['billingProfile']['billingAddressContinued'],
                billingCity: (string) $userData['billingProfile']['billingCity'],
                billingStateProvince: (string) $userData['billingProfile']['billingStateProvince'],
                billingPostalCode: (string) $userData['billingProfile']['billingPostalCode'],
            ),
        );

        $this->userApi->saveUser($user);
    }
}
