<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Dashboard\Responders;

use App\Context\Content\Entities\ContentItemCollection;
use App\Context\Issues\IssuesApi;
use App\Http\Entities\Meta;
use App\Http\Response\Support\Dashboard\Contracts\DashboardResponderContract;
use App\Http\Response\Support\IssueListing\Services\IssueLinkResolver;
use App\Persistence\QueryBuilders\Issues\IssueQueryBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DashboardResponderAdmin implements DashboardResponderContract
{
    public function __construct(
        private IssuesApi $issuesApi,
        private TwigEnvironment $twig,
        private IssueLinkResolver $issueLinkResolver,
        private ResponseFactoryInterface $responseFactory,
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
        $issues = $this->issuesApi->fetchIssues(
            queryBuilder: (new IssueQueryBuilder())
                ->withIsEnabled()
                ->withOrderBy(column: 'lastCommentAt', direction:'desc')
                ->withLimit(limit: 100),
        );

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/Dashboard/Templates/DashboardAdmin.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Support Dashboard',
                ),
                'issues' => $issues,
                'supportMenu' => $supportMenu,
                'issueLinkResolver' => $this->issueLinkResolver,
            ],
        ));

        return $response;
    }
}
