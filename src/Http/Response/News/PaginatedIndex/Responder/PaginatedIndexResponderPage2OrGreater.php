<?php

declare(strict_types=1);

namespace App\Http\Response\News\PaginatedIndex\Responder;

use App\Context\Content\Entities\ContentItemCollection;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use App\Http\Response\News\ItemLinkResolver;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PaginatedIndexResponderPage2OrGreater implements PaginatedIndexResponderContract
{
    public function __construct(
        private TwigEnvironment $twig,
        private ItemLinkResolver $itemLinkResolver,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     *
     * @phpstan-ignore-next-line
     */
    public function respond(
        ContentItemCollection $newsCollection,
        Pagination $pagination,
        Meta $meta,
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            'Http/Content/ContentListing.twig',
            [
                'meta' => $meta,
                'contentItemCollection' => $newsCollection,
                'pagination' => $pagination,
                'itemLinkResolver' => $this->itemLinkResolver,
                'breadcrumbSingle' => [
                    'content' => 'News Page 1',
                    'uri' => '/news',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Home',
                        'uri' => '/',
                    ],
                    [
                        'content' => 'News Page 1',
                        'uri' => '/news',
                    ],
                    ['content' => 'Pagination'],
                ],
            ],
        ));

        return $response;
    }
}
