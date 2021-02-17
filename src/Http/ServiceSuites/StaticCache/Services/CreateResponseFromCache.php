<?php

declare(strict_types=1);

namespace App\Http\ServiceSuites\StaticCache\Services;

use App\Http\ServiceSuites\StaticCache\Models\CacheItem;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function unserialize;

class CreateResponseFromCache
{
    private ResponseFactoryInterface $responseFactory;
    private GetCachePathFromRequest $getCachePathFromRequest;
    private Filesystem $filesystem;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        GetCachePathFromRequest $getCachePathFromRequest,
        Filesystem $filesystem
    ) {
        $this->responseFactory         = $responseFactory;
        $this->getCachePathFromRequest = $getCachePathFromRequest;
        $this->filesystem              = $filesystem;
    }

    /**
     * @throws FilesystemException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $content = $this->filesystem->read(
            ($this->getCachePathFromRequest)($request)
        );

        $cacheItem = unserialize($content);

        assert($cacheItem instanceof CacheItem);

        $response = $this->responseFactory->createResponse(
            $cacheItem->statusCode,
            $cacheItem->reasonPhrase
        )
            ->withProtocolVersion($cacheItem->protocolVersion);

        /**
         * @psalm-suppress MixedAssignment
         */
        foreach ($cacheItem->headers as $key => $val) {
            /**
             * @psalm-suppress MixedAssignment
             */
            foreach ($val as $headerVal) {
                /**
                 * @psalm-suppress MixedArgument
                 * @psalm-suppress MixedArgumentTypeCoercion
                 */
                $response = $response->withHeader(
                    $key,
                    $headerVal
                );
            }
        }

        $response->getBody()->write($cacheItem->body);

        return $response;
    }
}
