<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselEE;

use App\Context\Software\Entities\Software;
use App\Context\Software\SoftwareApi;
use App\Http\Response\Downloads\SoftwareFileDownload\SoftwareFileDownload;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;

class AnselEEDownloadAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private SoftwareFileDownload $download,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $ansel = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())
                ->withSlug('ansel-ee'),
        );

        assert($ansel instanceof Software);

        $version = $ansel->versions()->first();

        return $this->download->download(
            version: $version,
            request: $request,
        );
    }
}
