<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingCountryRegion
{
    /**
     * @Mapping\Column(
     *     name="billing_country_region",
     *     type="string",
     * )
     */
    protected string $billingCountryRegion = '';

    public function getBillingCountryRegion(): string
    {
        return $this->billingCountryRegion;
    }

    public function setBillingCountryRegion(string $billingCountryRegion): void
    {
        $this->billingCountryRegion = $billingCountryRegion;
    }
}
