<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\FileUpload;

use App\Context\TempFileStorage\TempFileStorageApi;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

use function assert;

class FileUploadAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TempFileStorageApi $tempFileStorageApi,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $file = $request->getUploadedFiles()['file'] ?? null;

        if ($file === null) {
            return $this->responseFactory->createResponse(
                400,
                'File upload not provided',
            );
        }

        assert($file instanceof UploadedFileInterface);

        $response = $this->responseFactory->createResponse()
            ->withHeader(
                'Content-type',
                'application/json',
            );

        $response->getBody()->write(
            $this->tempFileStorageApi->saveUploadedFile($file)
                ->toJson(),
        );

        return $response;
    }
}
