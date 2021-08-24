<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Search\Responders;

use App\Context\Issues\Entities\IssueCollection;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use App\Http\Response\Support\IssueListing\Services\IssueLinkResolver;
use App\Http\Response\Support\Search\Contracts\SearchIssuesResponderContract;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SearchIssuesResponderPage2OrGreater implements SearchIssuesResponderContract
{
    public function __construct(
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
        IssueCollection $issues,
        Pagination $pagination,
        Meta $meta,
        string $searchQuery,
        string $searchAction = '/support/search'
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/IssueListing/Templates/IssueListing.twig',
            [
                'meta' => $meta,
                'issues' => $issues,
                'pagination' => $pagination,
                'supportMenu' => $supportMenu,
                'searchValue' => $searchQuery,
                'searchAction' => $searchAction,
                'searchPlaceholder' => 'Search all issues',
                'issueLinkResolver' => $this->issueLinkResolver,
                'breadcrumbSingle' => [
                    'content' => 'Search Results Page 1',
                    'uri' => '/news',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Home',
                        'uri' => '/',
                    ],
                    [
                        'content' => 'Search Results Page 1',
                        'uri' => '/support/search?query=' . $searchQuery,
                    ],
                    ['content' => 'Pagination'],
                ],
            ],
        ));

        return $response;
    }
}
