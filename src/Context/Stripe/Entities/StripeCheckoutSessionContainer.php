<?php

declare(strict_types=1);

namespace App\Context\Stripe\Entities;

use Stripe\Checkout\Session;

class StripeCheckoutSessionContainer
{
    public function __construct(private Session $session)
    {
    }

    public function checkoutUrl(): string
    {
        /**
         * @psalm-suppress UndefinedMagicPropertyFetch
         * @phpstan-ignore-next-line
         */
        return (string) $this->session->url;
    }
}
