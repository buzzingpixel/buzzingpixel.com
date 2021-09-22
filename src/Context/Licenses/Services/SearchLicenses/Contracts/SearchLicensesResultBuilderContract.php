<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\SearchLicenses\Contracts;

use App\Context\Licenses\Entities\LicenseResult;
use App\Context\Licenses\Entities\SearchParams;

interface SearchLicensesResultBuilderContract
{
    /**
     * @param string[] $resultIds
     */
    public function buildResult(
        array $resultIds,
        SearchParams $searchParams,
    ): LicenseResult;
}
