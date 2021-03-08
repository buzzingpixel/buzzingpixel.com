<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait PercentComplete
{
    /**
     * @Mapping\Column(
     *     name="percent_complete",
     *     type="float",
     * )
     */
    protected float | int $percentComplete = 0.0;

    public function getPercentComplete(): float | int
    {
        return $this->percentComplete;
    }

    public function setPercentComplete(float | int $percentComplete): void
    {
        $this->percentComplete = $percentComplete;
    }
}
