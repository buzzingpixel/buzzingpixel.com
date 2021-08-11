<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use App\Context\Orders\Entities\OrderCollection;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedOrdersResponderFactory
{
    public function __construct(
        private PaginatedOrdersResponder $responder,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function createResponder(
        ServerRequestInterface $request,
        int $pageNum,
        OrderCollection $orders,
    ): PaginatedOrdersResponderContract {
        if ($pageNum > 1 && $orders->count() < 1) {
            return new PaginatedOrdersResponderInvalid(request: $request);
        }

        return $this->responder;
    }
}
