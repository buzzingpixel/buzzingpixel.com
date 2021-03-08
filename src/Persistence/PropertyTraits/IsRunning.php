<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsRunning
{
    /**
     * @Mapping\Column(
     *     name="is_running",
     *     type="boolean"
     * )
     */
    protected bool $isRunning = true;

    public function getIsRunning(): bool
    {
        return $this->isRunning;
    }

    public function setIsRunning(bool $isRunning): void
    {
        $this->isRunning = $isRunning;
    }
}
