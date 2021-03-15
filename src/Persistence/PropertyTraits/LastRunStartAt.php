<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait LastRunStartAt
{
    /**
     * @Mapping\Column(
     *     name="last_run_start_at",
     *     type="datetimetz_immutable",
     *     nullable=true
     * )
     */
    protected ?DateTimeImmutable $lastRunStartAt;

    public function getLastRunStartAt(): ?DateTimeImmutable
    {
        return $this->lastRunStartAt;
    }

    public function setLastRunStartAt(?DateTimeImmutable $lastRunStartAt): void
    {
        $this->lastRunStartAt = $lastRunStartAt;
    }
}
