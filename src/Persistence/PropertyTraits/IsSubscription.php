<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsSubscription
{
    /**
     * @Mapping\Column(
     *     name="is_subscription",
     *     type="boolean",
     * )
     */
    protected bool $isSubscription = false;

    public function getIsSubscription(): bool
    {
        return $this->isSubscription;
    }

    public function setIsSubscription(bool $isSubscription): void
    {
        $this->isSubscription = $isSubscription;
    }
}
