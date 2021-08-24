<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Search;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Pagination;
use App\Http\Response\Support\IssueListing\Factories\MetaFactory;
use App\Http\Response\Support\Search\Factories\SearchIssuesResponderFactory;
use App\Http\Response\Support\Search\Factories\SearchIssuesResultFactory;
use App\Http\Utilities\General\PageNumberUtil;
use Config\General;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SearchPublicPlusUsersIssuesAction
{
    private const PER_PAGE = 20;

    public function __construct(
        private General $config,
        private MetaFactory $metaFactory,
        private LoggedInUser $loggedInUser,
        private PageNumberUtil $pageNumberUtil,
        private SearchIssuesResponderFactory $responderFactory,
        private SearchIssuesResultFactory $searchIssuesResultFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string[] $queryParams */
        $queryParams = $request->getQueryParams();

        $query = ($queryParams['query'] ?? '');

        $pageNumString = (string) $request->getAttribute('pageNum');

        $pageNumber = $this->pageNumberUtil->pageNumberFromString(
            incoming: $pageNumString,
        );

        $pageNumberOrNull = $this->pageNumberUtil->pageNumberOrNullFromString(
            incoming: $pageNumString,
        );

        $issuesResult = $this->searchIssuesResultFactory->getSearchResults(
            query: $query,
            loggedInUser: $this->loggedInUser,
            pageNumber: $pageNumber,
            perPage: self::PER_PAGE,
        );

        $pagination = (new Pagination())
            ->withBase(val: '/support/search')
            ->withCurrentPage(val: $pageNumber)
            ->withPerPage(val: self::PER_PAGE)
            ->withTotalResults(
                val: $issuesResult->issuesResult()->absoluteTotal()
            )
            ->withQueryStringFromArray($queryParams);

        return $this->responderFactory->getResponder(
            pageNumber: $pageNumberOrNull,
            searchIssuesResult: $issuesResult,
            request: $request,
        )->respond(
            supportMenu: $this->config->supportMenu('allIssues'),
            issues: $issuesResult->issuesResult()->issueCollection(),
            pagination: $pagination,
            meta: $this->metaFactory->getMeta(
                pageNumber: $pageNumber,
                baseTitle: 'Search for "' . $query . '"',
            ),
            searchQuery: $query,
        );
    }
}
