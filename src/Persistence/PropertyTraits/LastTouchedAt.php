<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait LastTouchedAt
{
    /**
     * @Mapping\Column(
     *     name="last_touched_at",
     *     type="datetimetz_immutable",
     *     nullable=false
     * )
     */
    protected DateTimeImmutable $lastTouchedAt;

    public function getLastTouchedAt(): DateTimeImmutable
    {
        return $this->lastTouchedAt;
    }

    public function setLastTouchedAt(DateTimeImmutable $lastTouchedAt): void
    {
        $this->lastTouchedAt = $lastTouchedAt;
    }
}
