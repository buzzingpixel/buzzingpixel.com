<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use App\Context\Orders\Entities\OrderCollection;
use App\Http\Entities\Pagination;

class OrderResult
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private int $absoluteTotal,
        /** @phpstan-ignore-next-line */
        private OrderCollection $orders,
        private string $searchTerm,
        private Pagination $pagination,
    ) {
    }

    public function absoluteTotal(): int
    {
        return $this->absoluteTotal;
    }

    /** @phpstan-ignore-next-line */
    public function orders(): OrderCollection
    {
        return $this->orders;
    }

    public function searchTerm(): string
    {
        return $this->searchTerm;
    }

    public function pagination(): Pagination
    {
        return $this->pagination;
    }
}
