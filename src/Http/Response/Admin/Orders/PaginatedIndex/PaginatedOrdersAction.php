<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use App\Context\Orders\OrderApi;
use App\Http\Entities\Pagination;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedOrdersAction
{
    private const LIMIT = 20;

    public function __construct(
        private OrderApi $orderApi,
        private PaginatedOrdersResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string[] $queryParams */
        $queryParams = $request->getQueryParams();

        $pageNum = (int) ($queryParams['page'] ?? 1);
        $pageNum = $pageNum < 1 ? 1 : $pageNum;

        $queryBuilder = (new OrderQueryBuilder())
            ->withOrderBy('orderDate', 'desc')
            ->withOffset(($pageNum * self::LIMIT) - self::LIMIT)
            ->withLimit(self::LIMIT);

        $orders = $this->orderApi->fetchOrders(queryBuilder: $queryBuilder);

        $pagination = (new Pagination())
            ->withQueryStringBased(true)
            ->withBase('/admin/orders')
            ->withQueryStringFromArray($queryParams)
            ->withCurrentPage($pageNum)
            ->withPerPage(self::LIMIT)
            ->withTotalResults($this->orderApi->fetchTotalOrders(
                queryBuilder: $queryBuilder,
            ));

        return $this->responderFactory->createResponder(
            request: $request,
            pageNum: $pageNum,
            orders: $orders,
        )->respond(
            pagination: $pagination,
            orders: $orders,
        );
    }
}
