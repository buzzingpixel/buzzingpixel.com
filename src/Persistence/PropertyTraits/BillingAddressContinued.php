<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BillingAddressContinued
{
    /**
     * @Mapping\Column(
     *     name="billing_address_continued",
     *     type="string",
     * )
     */
    protected string $billingAddressContinued = '';

    public function getBillingAddressContinued(): string
    {
        return $this->billingAddressContinued;
    }

    public function setBillingAddressContinued(
        string $billingAddressContinued
    ): void {
        $this->billingAddressContinued = $billingAddressContinued;
    }
}
