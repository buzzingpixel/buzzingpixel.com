<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\SearchLicenses\Services;

use App\Context\Licenses\Entities\LicenseCollection;
use App\Context\Licenses\Entities\LicenseResult;
use App\Context\Licenses\Entities\SearchParams;
use App\Context\Licenses\Services\SearchLicenses\Contracts\SearchLicensesResultBuilderContract;

class SearchLicensesResultBuilderNoResults implements SearchLicensesResultBuilderContract
{
    /**
     * @inheritDoc
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): LicenseResult {
        return new LicenseResult(
            absoluteTotal: 0,
            licenses: new LicenseCollection(),
        );
    }
}
