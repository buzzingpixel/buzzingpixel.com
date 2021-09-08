<?php

declare(strict_types=1);

namespace App\ImportFromOldSite\OrphanedLicenses;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserBillingProfile;
use App\Context\Users\Entities\UserSupportProfile;
use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use DateTimeImmutable;
use DateTimeInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use function array_map;
use function assert;
use function json_decode;
use function var_export;

class OrphanedLicenses
{
    public function __construct(
        private Client $guzzle,
        private General $config,
        private UserApi $userApi,
        private LicenseApi $licenseApi,
        private SoftwareApi $softwareApi,
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function import(): void
    {
        $request = $this->guzzle->get(
            $this->config->oldSiteUrl(
                uri: '/new-site-transfer/orphaned-licenses'
            ),
            [
                'query' => ['key' => $this->config->oldSiteTransferKey()],
            ],
        );

        // dd(count(json_decode((string) $request->getBody(), true)));

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedArrayAccess
         */
        array_map(
            [$this, 'importItem'],
            json_decode((string) $request->getBody(), true)
        );
    }

    /**
     * @param mixed[] $item
     *
     * @throws InvalidEmailAddress
     * @throws InvalidPassword
     */
    protected function importItem(array $item): void
    {
        $alreadyImportedLicense = $this->licenseApi->fetchOneLicense(
            queryBuilder: (new LicenseQueryBuilder())
                ->withLicenseKey(value: (string) $item['licenseKey'])
        );

        if ($alreadyImportedLicense !== null) {
            return;
        }

        $user = $this->userApi->fetchOneUser(
            queryBuilder: (new UserQueryBuilder())
                ->withEmailAddress(value: (string) $item['userEmail']),
        );

        if ($user === null) {
            /** @psalm-suppress MixedAssignment */
            $userData = $item['userData'];

            /** @psalm-suppress MixedArrayAccess */
            $userCreatedAt = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                (string) $userData['createdAt'],
            );

            assert($userCreatedAt instanceof DateTimeInterface);

            /**
             * @psalm-suppress MixedArrayAccess
             */
            $user = new User(
                emailAddress: (string) $userData['emailAddress'],
                passwordHash: (string) $userData['passwordHash'],
                createdAt: $userCreatedAt,
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

            $userSavePayload = $this->userApi->saveUser($user);

            if ($userSavePayload->getStatus() !== Payload::STATUS_CREATED) {
                var_export($userSavePayload);
                die;
            }

            /** @psalm-suppress MixedAssignment */
            $user = $userSavePayload->getResult()['userEntity'];

            assert($user instanceof User);
        }

        $software = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())
                ->withSlug(value: (string) $item['softwareSlug']),
        );

        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        $license = new License(
            isDisabled: (bool) $item['isDisabled'],
            majorVersion: (string) $item['softwareVersion'],
            licenseKey: (string) $item['licenseKey'],
            userNotes: (string) $item['userNotes'],
            authorizedDomains: (array) $item['authorizedDomains'],
            user: $user,
            software: $software,
            isUpgrade: (bool) $item['isUpgrade'],
            hasBeenUpgraded: (bool) $item['hasBeenUpgraded'],
        );

        $payload = $this->licenseApi->saveLicense(license: $license);

        if ($payload->getStatus() === Payload::STATUS_CREATED) {
            return;
        }

        var_export($payload);
        die;
    }
}
