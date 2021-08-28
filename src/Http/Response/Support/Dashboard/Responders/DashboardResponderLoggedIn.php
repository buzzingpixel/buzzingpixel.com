<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Dashboard\Responders;

use App\Context\Content\Entities\ContentItemCollection;
use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Http\Response\Support\Dashboard\Contracts\DashboardResponderContract;
use App\Http\Response\Support\Dashboard\Services\SupportArticleLinkResolver;
use App\Http\Response\Support\IssueListing\Services\IssueLinkResolver;
use App\Persistence\QueryBuilders\Issues\IssueQueryBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DashboardResponderLoggedIn implements DashboardResponderContract
{
    public function __construct(
        private IssuesApi $issuesApi,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private IssueLinkResolver $issueLinkResolver,
        private ResponseFactoryInterface $responseFactory,
        private SupportArticleLinkResolver $supportArticleLinkResolver,
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     *
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function respond(
        array $supportMenu,
        ContentItemCollection $supportArticles,
    ): ResponseInterface {
        $mostRecentIssues = $this->issuesApi->fetchIssues(
            queryBuilder: (new IssueQueryBuilder())
                ->withIsEnabled()
                ->withUserId(value: $this->loggedInUser->user()->id())
                ->withOrderBy(column: 'lastCommentAt', direction:'desc')
                ->withLimit(limit: 6),
        );

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/Dashboard/Templates/DashboardLoggedIn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Support Dashboard',
                ),
                'supportMenu' => $supportMenu,
                'user' => $this->loggedInUser->user(),
                'supportArticles' => $supportArticles,
                'mostRecentIssues' => $mostRecentIssues,
                'searchAction' => '/support/search',
                'searchPlaceholder' => 'Search all issues',
                'issueLinkResolver' => $this->issueLinkResolver,
                'supportArticleLinkResolver' => $this->supportArticleLinkResolver,
            ],
        ));

        return $response;
    }
}
