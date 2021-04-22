<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsDisabled
{
    /**
     * @Mapping\Column(
     *     name="is_disabled",
     *     type="boolean"
     * )
     */
    protected bool $isDisabled = true;

    public function getIsDisabled(): bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(bool $isDisabled): void
    {
        $this->isDisabled = $isDisabled;
    }
}
