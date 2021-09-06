<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait HasBeenUpgraded
{
    /**
     * @Mapping\Column(
     *     name="has_been_upgraded",
     *     type="boolean",
     *     options={"default" : false},
     * )
     */
    protected bool $hasBeenUpgraded = true;

    public function getHasBeenUpgraded(): bool
    {
        return $this->hasBeenUpgraded;
    }

    public function setHasBeenUpgraded(bool $hasBeenUpgraded): void
    {
        $this->hasBeenUpgraded = $hasBeenUpgraded;
    }
}
