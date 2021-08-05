<?php

declare(strict_types=1);

namespace App\Context\Stripe;

use App\Context\Cart\Entities\Cart;
use App\Context\Stripe\Entities\StripeCustomerCollection;
use App\Context\Stripe\Entities\StripeInvoiceCollection;
use App\Context\Stripe\Entities\StripeInvoiceItemCollection;
use App\Context\Stripe\Entities\StripePriceCollection;
use App\Context\Stripe\Entities\StripeProductCollection;
use App\Context\Stripe\Entities\StripeSubscriptionCollection;
use App\Context\Stripe\Entities\StripeTaxRateCollection;
use App\Context\Stripe\Services\CreateBillingPortalSession;
use App\Context\Stripe\Services\CreateCheckoutSession;
use App\Context\Stripe\Services\StripeFetchCustomers;
use App\Context\Stripe\Services\StripeFetchInvoiceItems;
use App\Context\Stripe\Services\StripeFetchInvoices;
use App\Context\Stripe\Services\StripeFetchPrices;
use App\Context\Stripe\Services\StripeFetchProducts;
use App\Context\Stripe\Services\StripeFetchSubscriptions;
use App\Context\Stripe\Services\StripeFetchTaxRates;
use App\Context\Stripe\Services\SyncCustomers;
use App\Context\Stripe\Services\SyncLicenses;
use App\Context\Stripe\Services\SyncProducts;
use App\Context\Users\Entities\User;
use Stripe\BillingPortal\Session as BillingSession;
use Stripe\Checkout\Session as CheckoutSession;

class LocalStripeApi
{
    public function __construct(
        private SyncProducts $syncProducts,
        private SyncLicenses $syncLicenses,
        private SyncCustomers $syncCustomers,
        private StripeFetchPrices $fetchPrices,
        private StripeFetchInvoices $fetchInvoices,
        private StripeFetchProducts $fetchProducts,
        private StripeFetchTaxRates $fetchTaxRates,
        private StripeFetchCustomers $fetchCustomers,
        private StripeFetchInvoiceItems $fetchInvoiceItems,
        private CreateCheckoutSession $createCheckoutSession,
        private StripeFetchSubscriptions $fetchSubscriptions,
        private CreateBillingPortalSession $createBillingPortalSession,
    ) {
    }

    public function syncProducts(): void
    {
        $this->syncProducts->sync();
    }

    public function syncCustomers(): void
    {
        $this->syncCustomers->sync();
    }

    public function syncLicenses(): void
    {
        $this->syncLicenses->sync();
    }

    public function createBillingPortal(User $user): BillingSession
    {
        return $this->createBillingPortalSession->create($user);
    }

    public function createCheckoutSession(Cart $cart): CheckoutSession
    {
        return $this->createCheckoutSession->create($cart);
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetchCustomers(array $params = []): StripeCustomerCollection
    {
        return $this->fetchCustomers->fetch($params);
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetchInvoiceItems(array $params = []): StripeInvoiceItemCollection
    {
        return $this->fetchInvoiceItems->fetch($params);
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetchInvoices(array $params = []): StripeInvoiceCollection
    {
        return $this->fetchInvoices->fetch($params);
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetchPrices(array $params = []): StripePriceCollection
    {
        return $this->fetchPrices->fetch($params);
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetchProducts(array $params = []): StripeProductCollection
    {
        return $this->fetchProducts->fetch($params);
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetchSubscriptions(
        array $params = []
    ): StripeSubscriptionCollection {
        return $this->fetchSubscriptions->fetch($params);
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetchTaxRates(array $params = []): StripeTaxRateCollection
    {
        return $this->fetchTaxRates->fetch($params);
    }
}
