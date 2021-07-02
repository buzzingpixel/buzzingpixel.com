<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait UserStripeId
{
    /**
     * @Mapping\Column(
     *     name="user_stripe_id",
     *     type="string",
     * )
     */
    protected string $userStripeId = '';

    public function getUserStripeId(): string
    {
        return $this->userStripeId;
    }

    public function setUserStripeId(string $userStripeId): void
    {
        $this->userStripeId = $userStripeId;
    }
}
