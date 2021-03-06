<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use League\ISO3166\ISO3166 as ISO3166Source;
use Throwable;

trait BillingCountryRegion
{
    private string $billingCountryRegion;

    public function billingCountryRegion(): string
    {
        return $this->billingCountryRegion;
    }

    public function billingCountryRegionAlpha2(): string
    {
        return (string) ((new ISO3166Source())
            ->alpha3($this->billingCountryRegion())['alpha2']);
    }

    public function billingCountryRegionAlpha3(): string
    {
        return $this->billingCountryRegion();
    }

    public function billingCountryRegionNumeric(): string
    {
        return (string) ((new ISO3166Source())
            ->alpha3($this->billingCountryRegion())['alpha2']);
    }

    public function billingCountryRegionName(): string
    {
        try {
            return (string) ((new ISO3166Source())
                ->alpha3($this->billingCountryRegion())['name']);
        } catch (Throwable) {
            return '';
        }
    }

    /**
     * @return $this
     */
    public function withBillingCountryRegion(string $billingCountryRegion): self
    {
        $clone = clone $this;

        $clone->billingCountryRegion = $billingCountryRegion;

        return $clone;
    }
}
