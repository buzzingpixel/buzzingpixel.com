<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing;

use App\Context\Issues\IssuesApi;
use App\Http\Entities\Pagination;
use App\Http\Response\Support\IssueListing\Factories\MetaFactory;
use App\Http\Response\Support\IssueListing\Factories\PaginatedIndexResponderFactory;
use App\Http\Utilities\General\PageNumberUtil;
use App\Persistence\QueryBuilders\Support\IssueQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PublicIssuesPaginatedIndexAction
{
    private const PER_PAGE = 20;

    public function __construct(
        private General $config,
        private IssuesApi $issuesApi,
        private MetaFactory $metaFactory,
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

        $queryBuilder = (new IssueQueryBuilder())
            ->withIsEnabled()
            ->withIsPublic()
            ->withOrderBy(column: 'createdAt', direction: 'desc')
            ->withLimit(limit: self::PER_PAGE)
            ->withOffset(
                offset: ($pageNumber * self::PER_PAGE) - self::PER_PAGE
            );

        $totalIssues = $this->issuesApi->fetchTotalIssues(
            queryBuilder: $queryBuilder
        );

        $issues = $this->issuesApi->fetchIssues(queryBuilder: $queryBuilder);

        $pagination = (new Pagination())
            ->withBase(val: '/support/public')
            ->withCurrentPage(val: $pageNumber)
            ->withPerPage(val: self::PER_PAGE)
            ->withTotalResults(val: $totalIssues);

        return $this->responderFactory->getResponder(
            pageNumber: $pageNumberOrNull,
            issues: $issues,
            request: $request,
        )->respond(
            supportMenu: $this->config->supportMenu(active: 'publicIssues'),
            issues: $issues,
            pagination: $pagination,
            meta: $this->metaFactory->getMeta(
                pageNumber: $pageNumber,
                baseTitle: 'Public Issues',
            ),
        );
    }
}
