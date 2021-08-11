<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use App\Context\Orders\Entities\OrderCollection;
use App\Http\Entities\Pagination;
use Psr\Http\Message\ResponseInterface;

interface PaginatedOrdersResponderContract
{
    public function respond(
        Pagination $pagination,
        OrderCollection $orders,
    ): ResponseInterface;
}
