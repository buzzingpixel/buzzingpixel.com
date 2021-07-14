<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Context\Licenses\Entities\License;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;

class FetchOneLicense
{
    public function __construct(private FetchLicenses $fetchLicenses)
    {
    }

    public function fetch(LicenseQueryBuilder $queryBuilder): ?License
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->fetchLicenses->fetch(
            queryBuilder: $queryBuilder->withLimit(1),
        )->firstOrNull();
    }
}
