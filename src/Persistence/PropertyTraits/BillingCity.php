<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingCity
{
    /**
     * @Mapping\Column(
     *     name="billing_city",
     *     type="string",
     * )
     */
    protected string $billingCity = '';

    public function getBillingCity(): string
    {
        return $this->billingCity;
    }

    public function setBillingCity(string $billingCity): void
    {
        $this->billingCity = $billingCity;
    }
}
