<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Licenses\LicenseListing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LicenseListingAction
{
    public function __construct(
        private LicenseResultFactory $licenseResultFactory,
        private LicenseListingResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string[] $queryParams */
        $queryParams = $request->getQueryParams();

        $licenseResult = $this->licenseResultFactory->make(
            queryParams: $queryParams,
        );

        return $this->responderFactory->make(
            request: $request,
            licenseResult: $licenseResult,
        )->respond();
    }
}
