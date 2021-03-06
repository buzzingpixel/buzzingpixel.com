<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsActive
{
    /**
     * @Mapping\Column(
     *     name="is_active",
     *     type="boolean"
     * )
     */
    protected bool $isActive = true;

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }
}
