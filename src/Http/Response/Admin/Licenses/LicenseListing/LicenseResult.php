<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Licenses\LicenseListing;

use App\Context\Licenses\Entities\LicenseCollection;
use App\Http\Entities\Pagination;

class LicenseResult
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private int $absoluteTotal,
        /** @phpstan-ignore-next-line */
        private LicenseCollection $licenses,
        private string $searchTerm,
        private Pagination $pagination,
    ) {
    }

    public function absoluteTotal(): int
    {
        return $this->absoluteTotal;
    }

    /** @phpstan-ignore-next-line */
    public function licenses(): LicenseCollection
    {
        return $this->licenses;
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
