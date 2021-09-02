<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\DownloadVersion;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Http\Response\Downloads\SoftwareFileDownload\SoftwareFileDownload;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class DownloadVersionAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private SoftwareFileDownload $softwareFileDownload,
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $softwareSlug = (string) $request->getAttribute('softwareSlug');

        $software = $this->softwareApi->fetchOneSoftware(
            (new SoftwareQueryBuilder())
                ->withSlug($softwareSlug),
        );

        if ($software === null) {
            throw new HttpNotFoundException($request);
        }

        $versionSlug = (string) $request->getAttribute('versionSlug');

        $version = $software->versions()->filter(
            static fn (SoftwareVersion $v) => $v->version() === $versionSlug
        )->firstOrNull();

        if ($version === null) {
            throw new HttpNotFoundException($request);
        }

        return $this->softwareFileDownload->download(
            version: $version,
            request: $request,
        );
    }
}
