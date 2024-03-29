<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Dashboard\Responders;

use App\Context\Content\Entities\ContentItemCollection;
use App\Http\Entities\Meta;
use App\Http\Response\Support\Dashboard\Contracts\DashboardResponderContract;
use App\Http\Response\Support\Dashboard\Services\SupportArticleLinkResolver;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DashboardResponderLoggedOut implements DashboardResponderContract
{
    public function __construct(
        private TwigEnvironment $twig,
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
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/Dashboard/Templates/DashboardLoggedOut.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Support Dashboard',
                ),
                'supportMenu' => $supportMenu,
                'supportArticles' => $supportArticles,
                'supportArticleLinkResolver' => $this->supportArticleLinkResolver,
            ],
        ));

        return $response;
    }
}
