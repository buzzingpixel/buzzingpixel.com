<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Search\Responders;

use App\Context\Issues\Entities\IssueCollection;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use App\Http\Response\Support\Search\Contracts\SearchIssuesResponderContract;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SearchIssuesResponderNoResults implements SearchIssuesResponderContract
{
    public function __construct(
        private TwigEnvironment $twig,
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
        string $searchAction = '/support/search',
        array $statusFilter = [],
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/IssueListing/Templates/NoResults.twig',
            [
                'meta' => $meta,
                'supportMenu' => $supportMenu,
                'searchValue' => $searchQuery,
                'searchAction' => $searchAction,
                'searchPlaceholder' => 'Search all issues',
                'breadcrumbSingle' => [
                    'content' => 'All Issues',
                    'uri' => '/support/all-issues',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Home',
                        'uri' => '/',
                    ],
                    [
                        'content' => 'Issues',
                        'uri' => '/support/all-issues',
                    ],
                    ['content' => 'No Results'],
                ],
            ],
        ));

        return $response;
    }
}
