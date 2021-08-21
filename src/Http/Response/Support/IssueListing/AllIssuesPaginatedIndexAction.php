<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Pagination;
use App\Http\Response\Support\IssueListing\Factories\MetaFactory;
use App\Http\Response\Support\IssueListing\Factories\PaginatedIndexResponderFactory;
use App\Http\Utilities\General\PageNumberUtil;
use Config\General;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AllIssuesPaginatedIndexAction
{
    private const PER_PAGE = 20;

    public function __construct(
        private General $config,
        private IssuesApi $issuesApi,
        private MetaFactory $metaFactory,
        private LoggedInUser $loggedInUser,
        private PageNumberUtil $pageNumberUtil,
        private PaginatedIndexResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $pageNumString = (string) $request->getAttribute('pageNum');

        $pageNumber = $this->pageNumberUtil->pageNumberFromString(
            incoming: $pageNumString,
        );

        $pageNumberOrNull = $this->pageNumberUtil->pageNumberOrNullFromString(
            incoming: $pageNumString,
        );

        $issuesResult = $this->issuesApi->fetchAllPlusPrivateIssue(
            user: $this->loggedInUser->user(),
            fetchParams: new FetchParams(
                limit: self::PER_PAGE,
                offset: ($pageNumber * self::PER_PAGE) - self::PER_PAGE,
            ),
        );

        $pagination = (new Pagination())
            ->withBase(val: '/support/all-issues')
            ->withCurrentPage(val: $pageNumber)
            ->withPerPage(val: self::PER_PAGE)
            ->withTotalResults(val: $issuesResult->absoluteTotal());

        return $this->responderFactory->getResponder(
            pageNumber: $pageNumberOrNull,
            issues: $issuesResult->issueCollection(),
            request: $request,
        )->respond(
            supportMenu: $this->config->supportMenu(active: 'allIssues'),
            issues: $issuesResult->issueCollection(),
            pagination: $pagination,
            meta: $this->metaFactory->getMeta(
                pageNumber: $pageNumber,
                baseTitle: 'All Issues',
            ),
        );
    }
}
