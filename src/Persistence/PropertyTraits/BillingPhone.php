<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingPhone
{
    /**
     * @Mapping\Column(
     *     name="billing_phone",
     *     type="string",
     * )
     */
    protected string $billingPhone = '';

    public function getBillingPhone(): string
    {
        return $this->billingPhone;
    }

    public function setBillingPhone(string $billingPhone): void
    {
        $this->billingPhone = $billingPhone;
    }
}
