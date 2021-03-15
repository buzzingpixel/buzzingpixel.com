<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait LastRunEndAt
{
    /**
     * @Mapping\Column(
     *     name="last_run_end_at",
     *     type="datetimetz_immutable",
     *     nullable=true
     * )
     */
    protected ?DateTimeImmutable $lastRunEndAt;

    public function getLastRunEndAt(): ?DateTimeImmutable
    {
        return $this->lastRunEndAt;
    }

    public function setLastRunEndAt(?DateTimeImmutable $lastRunEndAt): void
    {
        $this->lastRunEndAt = $lastRunEndAt;
    }
}
