<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingPostalCode
{
    /**
     * @Mapping\Column(
     *     name="billing_postal_code",
     *     type="string",
     * )
     */
    protected string $billingPostalCode = '';

    public function getBillingPostalCode(): string
    {
        return $this->billingPostalCode;
    }

    public function setBillingPostalCode(string $billingPostalCode): void
    {
        $this->billingPostalCode = $billingPostalCode;
    }
}
