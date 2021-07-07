<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait HasBeenUpgraded
{
    /**
     * @Mapping\Column(
     *     name="has_been_upgrade",
     *     type="boolean"
     * )
     */
    protected bool $hasBeenUpgrade = true;

    public function getHasBeenUpgraded(): bool
    {
        return $this->hasBeenUpgrade;
    }

    public function setHasBeenUpgraded(bool $hasBeenUpgraded): void
    {
        $this->hasBeenUpgrade = $hasBeenUpgraded;
    }
}
