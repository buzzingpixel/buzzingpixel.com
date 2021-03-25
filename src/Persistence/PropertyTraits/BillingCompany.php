<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingCompany
{
    /**
     * @Mapping\Column(
     *     name="billing_company",
     *     type="string",
     * )
     */
    protected string $billingCompany = '';

    public function getBillingCompany(): string
    {
        return $this->billingCompany;
    }

    public function setBillingCompany(string $billingCompany): void
    {
        $this->billingCompany = $billingCompany;
    }
}
