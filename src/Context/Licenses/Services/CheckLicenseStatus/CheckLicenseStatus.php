<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\CheckLicenseStatus;

use App\Context\Licenses\Services\CheckLicenseStatus\Entities\CheckLicenseResult;
use App\Context\Licenses\Services\CheckLicenseStatus\Factories\CheckLicenseResultFactory;
use App\Context\Licenses\Services\FetchOneLicense;
use App\Context\Url\Entities\Url;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;

class CheckLicenseStatus
{
    public function __construct(
        private FetchOneLicense $fetchOneLicense,
        private CheckLicenseResultFactory $checkLicenseResultFactory
    ) {
    }

    public function check(
        string $slug,
        string $domain,
        string $license,
        string $version = '',
    ): CheckLicenseResult {
        return $this->checkLicenseResultFactory->getResult(
            domain: Url::createFromString($domain),
            version: $version,
            license: $this->fetchOneLicense->fetch(
                queryBuilder: (new LicenseQueryBuilder())
                    ->withLicenseKey(value: $license),
            ),
            softwareSlug: $slug,
        );
    }
}
