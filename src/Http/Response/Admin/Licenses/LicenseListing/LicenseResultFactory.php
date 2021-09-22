<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Licenses\LicenseListing;

use App\Context\Licenses\Entities\SearchParams;
use App\Context\Licenses\LicenseApi;
use App\Http\Entities\Pagination;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;

class LicenseResultFactory
{
    private const LIMIT = 20;

    public function __construct(private LicenseApi $licenseApi)
    {
    }

    /**
     * @param string[] $queryParams
     */
    public function make(array $queryParams): LicenseResult
    {
        $pageNum = (int) ($queryParams['page'] ?? 1);
        $pageNum = $pageNum < 1 ? 1 : $pageNum;

        $offset = ($pageNum * self::LIMIT) - self::LIMIT;

        $searchTerm = ($queryParams['search'] ?? '');

        if ($searchTerm === '') {
            $queryBuilder = (new LicenseQueryBuilder())
                ->withOffset($offset)
                ->withLimit(self::LIMIT);

            $absoluteTotal = $this->licenseApi->fetchTotalLicenses(
                queryBuilder: $queryBuilder,
            );

            return new LicenseResult(
                absoluteTotal: $absoluteTotal,
                licenses: $this->licenseApi->fetchLicenses(
                    queryBuilder: $queryBuilder,
                ),
                searchTerm: $searchTerm,
                pagination: (new Pagination())
                    ->withQueryStringBased(queryStringBased: true)
                    ->withBase(val: '/admin/licenses')
                    ->withQueryStringFromArray(val:$queryParams)
                    ->withCurrentPage(val: $pageNum)
                    ->withPerPage(val: self::LIMIT)
                    ->withTotalResults(val: $absoluteTotal),
            );
        }

        $apiResult = $this->licenseApi->searchLicenses(
            searchParams: new SearchParams(
                search: $searchTerm,
                limit: self::LIMIT,
                offset: $offset,
            )
        );

        return new LicenseResult(
            absoluteTotal: $apiResult->absoluteTotal(),
            licenses: $apiResult->licenses(),
            searchTerm: $searchTerm,
            pagination: (new Pagination())
                ->withQueryStringBased(queryStringBased: true)
                ->withBase(val: '/admin/licenses')
                ->withQueryStringFromArray(val:$queryParams)
                ->withCurrentPage(val: $pageNum)
                ->withPerPage(val: self::LIMIT)
                ->withTotalResults(val: $apiResult->absoluteTotal())
        );
    }
}
