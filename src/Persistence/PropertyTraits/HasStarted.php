<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait HasStarted
{
    /**
     * @Mapping\Column(
     *     name="has_started",
     *     type="boolean"
     * )
     */
    protected bool $hasStarted = true;

    public function getHasStarted(): bool
    {
        return $this->hasStarted;
    }

    public function setHasStarted(bool $hasStarted): void
    {
        $this->hasStarted = $hasStarted;
    }
}
