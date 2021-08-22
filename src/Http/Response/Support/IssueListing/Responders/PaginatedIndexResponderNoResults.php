<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing\Responders;

use App\Context\Issues\Entities\IssueCollection;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use App\Http\Response\Support\IssueListing\Contracts\PaginatedIndexResponderContract;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PaginatedIndexResponderNoResults implements PaginatedIndexResponderContract
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
        string $searchAction = '/support/search',
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/IssueListing/Templates/NoResults.twig',
            [
                'meta' => $meta,
                'supportMenu' => $supportMenu,
            ],
        ));

        return $response;
    }
}
