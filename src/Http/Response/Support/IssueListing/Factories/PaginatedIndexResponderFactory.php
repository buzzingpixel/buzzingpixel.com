<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing\Factories;

use App\Context\Issues\Entities\IssueCollection;
use App\Http\Response\Support\IssueListing\Contracts\PaginatedIndexResponderContract;
use App\Http\Response\Support\IssueListing\Responders\PaginatedIndexResponderInvalid;
use App\Http\Response\Support\IssueListing\Responders\PaginatedIndexResponderNoResults;
use App\Http\Response\Support\IssueListing\Responders\PaginatedIndexResponderPage1;
use App\Http\Response\Support\IssueListing\Responders\PaginatedIndexResponderPage2OrGreater;
use Psr\Http\Message\ServerRequestInterface;

class PaginatedIndexResponderFactory
{
    public function __construct(
        private PaginatedIndexResponderPage1 $page1,
        private PaginatedIndexResponderNoResults $noResults,
        private PaginatedIndexResponderPage2OrGreater $page2OrGreater,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function getResponder(
        ?int $pageNumber,
        IssueCollection $issues,
        ServerRequestInterface $request,
    ): PaginatedIndexResponderContract {
        if ($pageNumber === null || ($pageNumber > 1 && $issues->count() < 1)) {
            return new PaginatedIndexResponderInvalid($request);
        }

        if ($issues->count() < 1) {
            return $this->noResults;
        }

        if ($pageNumber > 1) {
            return $this->page2OrGreater;
        }

        return $this->page1;
    }
}
