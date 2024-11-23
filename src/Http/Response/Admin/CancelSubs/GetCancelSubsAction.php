<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\CancelSubs;

use App\Context\Stripe\LocalStripeApi;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stripe\Subscription;
use Twig\Environment as TwigEnvironment;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class GetCancelSubsAction
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LocalStripeApi $stripeApi,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $subscriptions = $this->stripeApi->fetchSubscriptions();

        $activeSubscriptions = $subscriptions->filter(
            static function (Subscription $subscription): bool {
                return $subscription->cancel_at_period_end === false;
            }
        );

        $canceledSubscriptions = $subscriptions->filter(
            static function (Subscription $subscription): bool {
                return $subscription->cancel_at_period_end;
            }
        );

        $adminMenu = $this->config->adminMenu();

        $adminMenu['cancel-subs']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/CancelSubs/CancelSubsInterface.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Exports | Admin',
                ),
                'accountMenu' => $adminMenu,
                'activeSubscriptions' => $activeSubscriptions->toArray(),
                'canceledSubscriptions' => $canceledSubscriptions->toArray(),
            ]
        ));

        return $response;
    }
}
