<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\PostAnalyticPageView;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

class PostAnalyticsPageViewResponder
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function respond(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(200)
            ->withHeader(
                'Content-type',
                'application/json'
            );

        $response->getBody()->write(
            (string) json_encode(['status' => 'ok'])
        );

        return $response;
    }
}
