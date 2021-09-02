<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses\Downloads;

use App\Context\Licenses\LicenseApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Account\Licenses\Downloads\Factories\DownloadResponderFactory;
use App\Http\Response\Account\Licenses\Downloads\Factories\VersionFromLicenseFactory;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DownloadAction
{
    public function __construct(
        private LicenseApi $licenseApi,
        private LoggedInUser $loggedInUser,
        private DownloadResponderFactory $responderFactory,
        private VersionFromLicenseFactory $versionFromLicenseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $licenseKey = (string) $request->getAttribute('licenseKey');

        $version = $this->versionFromLicenseFactory->getVersion(
            license: $this->licenseApi->fetchOneLicense(
                queryBuilder: (new LicenseQueryBuilder())
                    ->withUserId($this->loggedInUser->user()->id())
                    ->withLicenseKey($licenseKey),
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
