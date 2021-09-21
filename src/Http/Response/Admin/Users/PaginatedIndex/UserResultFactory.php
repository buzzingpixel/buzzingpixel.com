<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\PaginatedIndex;

use App\Context\Users\Entities\SearchParams;
use App\Context\Users\UserApi;
use App\Http\Entities\Pagination;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;

class UserResultFactory
{
    private const LIMIT = 20;

    public function __construct(private UserApi $userApi)
    {
    }

    /**
     * @param string[] $queryParams
     */
    public function make(array $queryParams): UserResult
    {
        $pageNum = (int) ($queryParams['page'] ?? 1);
        $pageNum = $pageNum < 1 ? 1 : $pageNum;

        $offset = ($pageNum * self::LIMIT) - self::LIMIT;

        $searchTerm = ($queryParams['search'] ?? '');

        if ($searchTerm === '') {
            $queryBuilder = (new UserQueryBuilder())
                ->withOrderBy('emailAddress', 'asc')
                ->withOffset($offset)
                ->withLimit(self::LIMIT);

            $absoluteTotal = $this->userApi->fetchTotalUsers(
                queryBuilder: $queryBuilder,
            );

            return new UserResult(
                absoluteTotal: $absoluteTotal,
                users: $this->userApi->fetchUsers(
                    queryBuilder: $queryBuilder,
                ),
                searchTerm: $searchTerm,
                pagination: (new Pagination())
                    ->withQueryStringBased(queryStringBased: true)
                    ->withBase(val: '/admin/users')
                    ->withQueryStringFromArray(val: $queryParams)
                    ->withCurrentPage(val: $pageNum)
                    ->withPerPage(val: self::LIMIT)
                    ->withTotalResults(val: $absoluteTotal),
            );
        }

        $apiResult = $this->userApi->searchUsers(
            searchParams: new SearchParams(search: $searchTerm),
        );

        return new UserResult(
            absoluteTotal: $apiResult->absoluteTotal(),
            users: $apiResult->users(),
            searchTerm: $searchTerm,
            pagination: (new Pagination())
                ->withQueryStringBased(queryStringBased: true)
                ->withBase(val: '/admin/users')
                ->withQueryStringFromArray(val: $queryParams)
                ->withCurrentPage(val: $pageNum)
                ->withPerPage(val: self::LIMIT)
                ->withTotalResults(val: $apiResult->absoluteTotal()),
        );
    }
}
