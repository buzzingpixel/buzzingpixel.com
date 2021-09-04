<?php

declare(strict_types=1);

namespace App\Http\Response\StandardPage;

use App\Context\Content\ContentApi;
use App\Context\Path\PathApi;
use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment as TwigEnvironment;

class StandardPageAction
{
    public function __construct(
        private PathApi $pathApi,
        private TwigEnvironment $twig,
        private ContentApi $contentApi,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $contentPath = (string) $request->getAttribute('contentPath');

        $contentItem = $this->contentApi->contentItemFromFile(
            path: $this->pathApi->pathFromProjectRoot(
                path: $contentPath,
            ),
        );

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            'Http/Content/ContentDetail.twig',
            [
                'meta' => new Meta(
                    metaTitle: $contentItem->title(),
                ),
                'showDate' => false,
                'showByLine' => false,
                'contentItem' => $contentItem,
            ],
        ));

        return $response;
    }
}
