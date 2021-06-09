<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Cart\Entities\Cart;
use App\Context\Stripe\Entities\StripePriceCollection;
use App\Context\Stripe\Factories\FetchPricesForSoftwareFactory;
use App\Context\Stripe\Factories\StripeFactory;
use Config\General;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Product;
use Stripe\TaxRate;

class CreateCheckoutSession
{
    public function __construct(
        private General $config,
        private StripeFactory $stripeFactory,
        private StripeFetchCustomers $stripeFetchCustomers,
        private FetchPricesForSoftwareFactory $fetchPricesForSoftwareFactory,
        private StripeFetchProducts $stripeFetchProducts,
        private StripeFetchTaxRates $stripeFetchTaxRates,
    ) {
    }

    public function create(Cart $cart): Session
    {
        $products = $this->stripeFetchProducts->fetch();

        $prices = new StripePriceCollection();

        foreach ($cart->cartItems()->toArray() as $cartItem) {
            $itemPrices = $this->fetchPricesForSoftwareFactory->createFetchPricesForSoftware(
                $cartItem->software()
            )->fetch();

            $itemPrices->map(static fn (Price $p) => $prices->add($p));
        }

        $customer = $this->stripeFetchCustomers->fetch([
            'email' => $cart->userGuarantee()->emailAddress(),
        ])->first();

        $lineItems = [];

        $prices->map(
            function (Price $price) use (
                &$lineItems,
                $products,
                $cart
            ): void {
                $product = $products->filter(
                    static fn (Product $p) => $p->id === $price->product,
                )->first();

                $cartItem = $cart->cartItems()
                    ->where(
                        'slug',
                        $product->metadata['slug']
                    )
                    ->first();

                $lineItem = [
                    'price' => $price->id,
                    'quantity' => $cartItem->quantity(),
                ];

                if ($cart->requiresTax()) {
                    $taxRate = $this->stripeFetchTaxRates->fetch()->filter(
                        static fn (TaxRate $r) => $r->state === 'TN',
                    )->first();

                    $lineItem['tax_rates'] = [$taxRate->id];
                }

                /**
                 * @psalm-suppress MixedArrayAssignment
                 */
                $lineItems[] = $lineItem;
            }
        );

        return $this->stripeFactory->createStripeClient()
            ->checkout
            ->sessions
            ->create([
                'cancel_url' => $this->config->siteUrl() . '/cart',
                'mode' => $cart->anyItemHasSubscription() ?
                    'subscription' :
                    'payment',
                'payment_method_types' => ['card'],
                'success_url' => $this->config->siteUrl() . '/cart/checkout-success',
                'customer' => $customer->id,
                'line_items' => $lineItems,
            ]);
    }
}
