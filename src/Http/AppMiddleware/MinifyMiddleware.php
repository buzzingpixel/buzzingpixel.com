<?php

declare(strict_types=1);

namespace App\Http\AppMiddleware;

use App\Factories\StreamFactory;
use App\Http\Utilities\Minify\Minifier;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Safe\Exceptions\FilesystemException;

use function trim;

class MinifyMiddleware implements MiddlewareInterface
{
    private Minifier $minifier;
    private StreamFactory $streamFactory;

    public function __construct(
        Minifier $minifier,
        StreamFactory $streamFactory
    ) {
        $this->minifier      = $minifier;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @throws FilesystemException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle($request);

        $contentType = $response->getHeader('Content-Type');

        $contentTypeString = $contentType[0] ?? 'text/html';

        if ($contentTypeString !== 'text/html') {
            return $response;
        }

        $content = (string) $response->getBody();

        if (trim($content) === '') {
            return $response;
        }

        $body = $this->streamFactory->make();

        $body->write(($this->minifier)($content));

        return $response->withBody($body);
    }
}
