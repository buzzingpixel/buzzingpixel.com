<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedOrdersAction
{
    public function __construct(
        private OrderResultFactory $orderResultFactory,
        private PaginatedOrdersResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string[] $queryParams */
        $queryParams = $request->getQueryParams();

        $orderResult = $this->orderResultFactory->make(
            queryParams: $queryParams,
        );

        return $this->responderFactory->make(
            request: $request,
            orderResult: $orderResult,
        )->respond();
    }
}
