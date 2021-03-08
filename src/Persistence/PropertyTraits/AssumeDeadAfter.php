<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait AssumeDeadAfter
{
    /**
     * @Mapping\Column(
     *     name="assume_dead_after",
     *     type="datetimetz_immutable",
     *     nullable=false
     * )
     */
    protected DateTimeImmutable $assumeDeadAfter;

    public function getAssumeDeadAfter(): DateTimeImmutable
    {
        return $this->assumeDeadAfter;
    }

    public function setAssumeDeadAfter(DateTimeImmutable $assumeDeadAfter): void
    {
        $this->assumeDeadAfter = $assumeDeadAfter;
    }
}
