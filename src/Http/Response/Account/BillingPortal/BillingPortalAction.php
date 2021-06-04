<?php

declare(strict_types=1);

namespace App\Http\Response\Account\BillingPortal;

use App\Context\Stripe\LocalStripeApi;
use App\Context\Users\Entities\LoggedInUser;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class BillingPortalAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private LocalStripeApi $stripeApi,
        private LoggedInUser $loggedInUser,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $session = $this->stripeApi->createBillingPortal(
            $this->loggedInUser->user()
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', $session->url);
    }
}
