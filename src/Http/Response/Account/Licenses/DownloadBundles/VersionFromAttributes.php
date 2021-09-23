<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses\DownloadBundles;

use App\Context\Licenses\LicenseApi;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;

class VersionFromAttributes
{
    public function __construct(
        private LicenseApi $licenseApi,
        private SoftwareApi $softwareApi,
        private LoggedInUser $loggedInUser,
    ) {
    }

    public function retrieve(
        string $licenseKey,
        string $softwareSlug,
    ): ?SoftwareVersion {
        $license = $this->licenseApi->fetchOneLicense(
            queryBuilder: (new LicenseQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withHasBeenUpgraded(false)
                ->withLicenseKey($licenseKey),
        );

        if ($license === null) {
            return null;
        }

        $baseSoftware = $license->softwareGuarantee();

        if (! $baseSoftware->isBundle()) {
            return null;
        }

        $software = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())
                ->withSlug($softwareSlug),
        );

        if ($software === null) {
            return null;
        }

        $version = $software->versions()->filter(
            static fn (
                SoftwareVersion $v
            ) => $v->downloadFile() !== '',
        )->firstOrNull();

        if ($version === null) {
            return null;
        }

        return $version;
    }
}
