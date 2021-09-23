<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses\DownloadBundles;

use App\Http\Response\Account\Licenses\Downloads\Factories\DownloadResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DownloadBundleAction
{
    public function __construct(
        private VersionFromAttributes $versionFromAttributes,
        private DownloadResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $version = $this->versionFromAttributes->retrieve(
            licenseKey: (string) $request->getAttribute('licenseKey'),
            softwareSlug: (string) $request->getAttribute(
                'softwareSlug'
            ),
        );

        return $this->responderFactory->getResponder(
            version: $version
        )->respond(
            version: $version,
            request: $request,
        );
    }
}
