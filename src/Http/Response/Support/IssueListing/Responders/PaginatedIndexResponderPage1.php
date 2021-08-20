<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing\Responders;

use App\Context\Issues\Entities\IssueCollection;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use App\Http\Response\Support\IssueListing\Contracts\PaginatedIndexResponderContract;
use App\Http\Response\Support\IssueListing\Services\IssueLinkResolver;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PaginatedIndexResponderPage1 implements PaginatedIndexResponderContract
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
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/IssueListing/Templates/IssueListing.twig',
            [
                'meta' => $meta,
                'issues' => $issues,
                'pagination' => $pagination,
                'supportMenu' => $supportMenu,
                'searchAction' => '/support/public/search',
                'searchPlaceholder' => 'Search public issues',
                'issueLinkResolver' => $this->issueLinkResolver,
            ],
        ));

        return $response;
    }
}
