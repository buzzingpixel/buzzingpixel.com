<?php

declare(strict_types=1);

namespace App\Context\Licenses;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Entities\LicenseCollection;
use App\Context\Licenses\Services\CancelSubscription;
use App\Context\Licenses\Services\CheckLicenseStatus\CheckLicenseStatus;
use App\Context\Licenses\Services\CheckLicenseStatus\Entities\CheckLicenseResult;
use App\Context\Licenses\Services\FetchLicenses;
use App\Context\Licenses\Services\FetchOneLicense;
use App\Context\Licenses\Services\GenerateLicenseKey;
use App\Context\Licenses\Services\ResumeSubscription;
use App\Context\Licenses\Services\SaveLicense;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;

class LicenseApi
{
    public function __construct(
        private SaveLicense $saveLicense,
        private FetchLicenses $fetchLicenses,
        private FetchOneLicense $fetchOneLicense,
        private CancelSubscription $cancelSubscription,
        private ResumeSubscription $resumeSubscription,
        private GenerateLicenseKey $generateLicenseKey,
        private CheckLicenseStatus $checkLicenseStatus,
    ) {
    }

    public function saveLicense(License $license): Payload
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->saveLicense->save($license);
    }

    /** @phpstan-ignore-next-line */
    public function fetchLicenses(
        LicenseQueryBuilder $queryBuilder,
    ): LicenseCollection {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->fetchLicenses->fetch($queryBuilder);
    }

    public function fetchOneLicense(
        LicenseQueryBuilder $queryBuilder,
    ): ?License {
        return $this->fetchOneLicense->fetch($queryBuilder);
    }

    public function cancelSubscription(License $license): void
    {
        $this->cancelSubscription->cancel($license);
    }

    public function resumeSubscription(License $license): void
    {
        $this->resumeSubscription->resume($license);
    }

    public function generateLicenseKey(): string
    {
        return $this->generateLicenseKey->generate();
    }

    public function checkLicenseStatus(
        string $slug,
        string $domain,
        string $license,
        string $version = '',
    ): CheckLicenseResult {
        return $this->checkLicenseStatus->check(
            slug: $slug,
            domain: $domain,
            license: $license,
            version: $version,
        );
    }
}
