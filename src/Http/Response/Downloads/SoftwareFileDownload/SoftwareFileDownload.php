<?php

declare(strict_types=1);

namespace App\Http\Response\Downloads\SoftwareFileDownload;

use App\Context\Path\PathApi;
use App\Context\Software\Entities\SoftwareVersion;
use App\Http\Response\Downloads\Entities\ResourceServlet;
use DaveRandom\Resume\FileResource;
use DaveRandom\Resume\InvalidRangeHeaderException;
use DaveRandom\Resume\NonExistentFileException;
use DaveRandom\Resume\RangeSet;
use DaveRandom\Resume\SendFileFailureException;
use DaveRandom\Resume\UnreadableFileException;
use DaveRandom\Resume\UnsatisfiableRangeException;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SoftwareFileDownload
{
    public function __construct(
        private PathApi $pathApi,
        private FinfoMimeTypeDetector $mimeTypeDetector,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function download(
        SoftwareVersion $version,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $filePath = $this->pathApi->pathFromProjectRoot(
            'storage/softwareDownloads/' . $version->downloadFile(),
        )->toString();

        $mimeType = $this->mimeTypeDetector->detectMimeTypeFromFile(
            $filePath,
        );

        if ($mimeType === null) {
            $mimeType = $this->mimeTypeDetector->detectMimeTypeFromPath(
                $filePath,
            );
        }

        $response = $this->responseFactory->createResponse();

        try {
            $params = $request->getServerParams();

            $rangeHeader = (string) ($params['HTTP_RANGE'] ?? '');

            $rangeSet = null;

            if ($rangeHeader !== '') {
                $rangeSet = RangeSet::createFromHeader($rangeHeader);
            }

            $resource = new FileResource(
                $filePath,
                $mimeType,
            );

            $servlet = new ResourceServlet(resource: $resource);

            $servlet->sendResource(
                rangeSet: $rangeSet,
                request: $request,
            );

            exit;
        } catch (InvalidRangeHeaderException $e) {
            $response = $response->withStatus(
                400,
                'Bad Request'
            );
        } catch (UnsatisfiableRangeException $e) {
            $response = $response->withStatus(
                416,
                'Range Not Satisfiable'
            );
        } catch (NonExistentFileException $e) {
            $response = $response->withStatus(
                404,
                'Not Found'
            );
        } catch (UnreadableFileException $e) {
            $response = $response->withStatus(
                500,
                'Internal Server Error'
            );
        } catch (SendFileFailureException $e) {
            $response = $response->withStatus(
                500,
                'Internal Server Error'
            );
        }

        return $response;
    }
}
