<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Search\Factories;

use App\Http\Response\Support\Search\Contracts\SearchIssuesResponderContract;
use App\Http\Response\Support\Search\Entities\SearchIssuesResult;
use App\Http\Response\Support\Search\Responders\SearchIssuesResponderInvalid;
use App\Http\Response\Support\Search\Responders\SearchIssuesResponderNoResults;
use App\Http\Response\Support\Search\Responders\SearchIssuesResponderPage1;
use App\Http\Response\Support\Search\Responders\SearchIssuesResponderPage2OrGreater;
use Psr\Http\Message\ServerRequestInterface;

class SearchIssuesResponderFactory
{
    public function __construct(
        private SearchIssuesResponderPage1 $page1,
        private SearchIssuesResponderNoResults $noResults,
        private SearchIssuesResponderPage2OrGreater $page2,
    ) {
    }

    public function getResponder(
        ?int $pageNumber,
        SearchIssuesResult $searchIssuesResult,
        ServerRequestInterface $request,
    ): SearchIssuesResponderContract {
        $count = $searchIssuesResult->issuesResult()
            ->issueCollection()
            ->count();

        if (
            $searchIssuesResult->isInvalid() ||
            $pageNumber === null ||
            ($pageNumber > 1 && $count < 1)
        ) {
            return new SearchIssuesResponderInvalid(request: $request);
        }

        if ($count < 1) {
            return $this->noResults;
        }

        if ($pageNumber > 1) {
            return $this->page2;
        }

        return $this->page1;
    }
}
