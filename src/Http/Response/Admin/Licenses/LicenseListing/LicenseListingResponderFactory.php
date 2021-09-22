<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Licenses\LicenseListing;

use App\Context\Users\Entities\LoggedInUser;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment as TwigEnvironment;

class LicenseListingResponderFactory
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function make(
        LicenseResult $licenseResult,
        ServerRequestInterface $request,
    ): LicenseListingResponderContract {
        $pageNum = $licenseResult->pagination()->currentPage();

        $licenses = $licenseResult->licenses();

        if ($pageNum > 1 && $licenses->count() < 1) {
            return new LicenseListingResponderInvalid(request: $request);
        }

        return new LicenseListingResponder(
            twig: $this->twig,
            config: $this->config,
            licenseResult: $licenseResult,
            loggedInUser: $this->loggedInUser,
            responseFactory: $this->responseFactory,
        );
    }
}
