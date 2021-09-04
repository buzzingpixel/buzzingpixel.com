<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\UpdateMaxVersionOnLicenses;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;

class UpdateMaxVersionOnLicenses
{
    public function __construct(
        private UpdateMaxVersionOnSubscriptionLicenses $subscriptions,
        private UpdateMaxVersionOnNonSubscriptionLicenses $nonSubscriptions,
    ) {
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException
     */
    public function update(): void
    {
        $this->subscriptions->update();
        $this->nonSubscriptions->update();
    }
}
