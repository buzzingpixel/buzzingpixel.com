<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsUpgrade
{
    /**
     * @Mapping\Column(
     *     name="is_upgrade",
     *     type="boolean",
     * )
     */
    protected bool $isUpgrade = false;

    public function getIsUpgrade(): bool
    {
        return $this->isUpgrade;
    }

    public function setIsUpgrade(bool $isUpgrade): void
    {
        $this->isUpgrade = $isUpgrade;
    }
}
