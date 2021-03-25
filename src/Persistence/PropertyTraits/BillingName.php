<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingName
{
    /**
     * @Mapping\Column(
     *     name="billing_name",
     *     type="string",
     * )
     */
    protected string $billingName = '';

    public function getBillingName(): string
    {
        return $this->billingName;
    }

    public function setBillingName(string $billingName): void
    {
        $this->billingName = $billingName;
    }
}
