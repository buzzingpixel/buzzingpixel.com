<?php

declare(strict_types=1);

namespace App\Http\Response\Software;

use App\Context\Software\SoftwareApi;
use App\Context\Stripe\LocalStripeApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class SoftwarePurchaseAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private LoggedInUser $loggedInUser,
        private LocalStripeApi $localStripeApi,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $softwareSlug = (string) $request->getAttribute('softwareSlug');

        $software = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())
                ->withSlug($softwareSlug),
        );

        if ($software === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $session = $this->localStripeApi->createCheckoutSessionForSoftware(
            software: $software,
            user: $this->loggedInUser->user(),
        );

        return $this->responseFactory->createResponse(303)->withHeader(
            'Location',
            $session->checkoutUrl(),
        );
    }
}
