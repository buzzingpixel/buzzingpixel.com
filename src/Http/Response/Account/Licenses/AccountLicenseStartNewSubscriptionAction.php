<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Context\Licenses\LicenseApi;
use App\Context\Stripe\LocalStripeApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class AccountLicenseStartNewSubscriptionAction
{
    public function __construct(
        private LicenseApi $licenseApi,
        private LoggedInUser $loggedInUser,
        private LocalStripeApi $localStripeApi,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $licenseKey = (string) $request->getAttribute('licenseKey');

        $license = $this->licenseApi->fetchOneLicense(
            (new LicenseQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withHasBeenUpgraded(false)
                ->withLicenseKey($licenseKey),
        );

        if ($license === null || $license->isDisabled()) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $session = $this->localStripeApi->createCheckoutSessionForLicense(
            license: $license,
        );

        return $this->responseFactory->createResponse(303)->withHeader(
            'Location',
            $session->checkoutUrl(),
        );
    }
}
