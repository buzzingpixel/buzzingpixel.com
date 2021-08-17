<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsEnabled
{
    /**
     * @Mapping\Column(
     *     name="is_enabled",
     *     type="boolean"
     * )
     */
    protected bool $isEnabled = true;

    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }
}
