<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\CancelSubs;

use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Stripe\LocalStripeApi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stripe\StripeClient;
use Stripe\Subscription;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class PostCancelSubsAction
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private LocalStripeApi $stripeApi,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $subscriptions = $this->stripeApi->fetchSubscriptions();

        $subscriptions = $subscriptions->filter(
            static function (Subscription $subscription): bool {
                return $subscription->cancel_at_period_end === false;
            }
        );

        $subscriptions->map(
            function (Subscription $subscription): void {
                $this->stripeClient->subscriptions->cancel(
                    $subscription->id,
                );
            }
        );

        return $response->withStatus(302)->withHeader(
            'Location',
            '/admin/cancel-subs',
        );
    }
}
