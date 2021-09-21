<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\PaginatedIndex;

use App\Context\Users\Entities\UserCollection;
use App\Http\Entities\Pagination;

class UserResult
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private int $absoluteTotal,
        /** @phpstan-ignore-next-line */
        private UserCollection $users,
        private string $searchTerm,
        private Pagination $pagination
    ) {
    }

    public function absoluteTotal(): int
    {
        return $this->absoluteTotal;
    }

    /** @phpstan-ignore-next-line */
    public function users(): UserCollection
    {
        return $this->users;
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
