<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingStateProvince
{
    /**
     * @Mapping\Column(
     *     name="billing_state_province",
     *     type="string",
     * )
     */
    protected string $billingStateProvince = '';

    public function getBillingStateProvince(): string
    {
        return $this->billingStateProvince;
    }

    public function setBillingStateProvince(string $billingStateProvince): void
    {
        $this->billingStateProvince = $billingStateProvince;
    }
}
