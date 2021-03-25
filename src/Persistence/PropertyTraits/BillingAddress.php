<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingAddress
{
    /**
     * @Mapping\Column(
     *     name="billing_address",
     *     type="string",
     * )
     */
    protected string $billingAddress = '';

    public function getBillingAddress(): string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(string $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }
}
