<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use App\Context\Orders\Entities\SearchParams;
use App\Context\Orders\OrderApi;
use App\Http\Entities\Pagination;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;

class OrderResultFactory
{
    private const LIMIT = 20;

    public function __construct(private OrderApi $orderApi)
    {
    }

    /**
     * @param string[] $queryParams
     */
    public function make(array $queryParams): OrderResult
    {
        $pageNum = (int) ($queryParams['page'] ?? 1);
        $pageNum = $pageNum < 1 ? 1 : $pageNum;

        $offset = ($pageNum * self::LIMIT) - self::LIMIT;

        $searchTerm = ($queryParams['search'] ?? '');

        if ($searchTerm === '') {
            $queryBuilder = (new OrderQueryBuilder())
                ->withOrderBy('orderDate', 'desc')
                ->withOffset($offset)
                ->withLimit(self::LIMIT);

            $absoluteTotal = $this->orderApi->fetchTotalOrders(
                queryBuilder: $queryBuilder,
            );

            return new OrderResult(
                absoluteTotal: $absoluteTotal,
                orders: $this->orderApi->fetchOrders(
                    queryBuilder: $queryBuilder
                ),
                searchTerm: $searchTerm,
                pagination: (new Pagination())
                    ->withQueryStringBased(queryStringBased: true)
                    ->withBase(val: '/admin/orders')
                    ->withQueryStringFromArray(val:$queryParams)
                    ->withCurrentPage(val: $pageNum)
                    ->withPerPage(val: self::LIMIT)
                    ->withTotalResults(val: $absoluteTotal)
            );
        }

        $orderApiResult = $this->orderApi->searchOrders(
            searchParams: new SearchParams(
                search: $searchTerm,
                limit: self::LIMIT,
                offset: $offset,
            )
        );

        return new OrderResult(
            absoluteTotal: $orderApiResult->absoluteTotal(),
            orders: $orderApiResult->orders(),
            searchTerm: $searchTerm,
            pagination: (new Pagination())
                ->withQueryStringBased(queryStringBased: true)
                ->withBase(val: '/admin/orders')
                ->withQueryStringFromArray(val:$queryParams)
                ->withCurrentPage(val: $pageNum)
                ->withPerPage(val: self::LIMIT)
                ->withTotalResults(val: $orderApiResult->absoluteTotal())
        );
    }
}
