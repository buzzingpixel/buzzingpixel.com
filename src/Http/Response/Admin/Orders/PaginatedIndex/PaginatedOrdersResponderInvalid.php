<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use App\Context\Orders\Entities\OrderCollection;
use App\Http\Entities\Pagination;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class PaginatedOrdersResponderInvalid implements PaginatedOrdersResponderContract
{
    public function __construct(
        private ServerRequestInterface $request
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function respond(
        Pagination $pagination,
        OrderCollection $orders,
    ): ResponseInterface {
        throw new HttpNotFoundException($this->request);
    }
}
