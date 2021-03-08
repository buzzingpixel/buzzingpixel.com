<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsFinished
{
    /**
     * @Mapping\Column(
     *     name="is_finished",
     *     type="boolean",
     * )
     */
    protected bool $isFinished = false;

    public function getIsFinished(): bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): void
    {
        $this->isFinished = $isFinished;
    }
}
