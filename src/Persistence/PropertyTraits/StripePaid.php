<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripePaid
{
    /**
     * @Mapping\Column(
     *     name="stripe_paid",
     *     type="boolean"
     * )
     */
    protected bool $stripePaid = true;

    public function getStripePaid(): bool
    {
        return $this->stripePaid;
    }

    public function setStripePaid(bool $stripePaid): void
    {
        $this->stripePaid = $stripePaid;
    }
}
