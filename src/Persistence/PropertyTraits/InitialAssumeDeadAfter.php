<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait InitialAssumeDeadAfter
{
    /**
     * @Mapping\Column(
     *     name="initial_assume_dead_after",
     *     type="datetimetz_immutable",
     *     nullable=false
     * )
     */
    protected DateTimeImmutable $initialInitialAssumeDeadAfter;

    public function getInitialAssumeDeadAfter(): DateTimeImmutable
    {
        return $this->initialInitialAssumeDeadAfter;
    }

    public function setInitialAssumeDeadAfter(DateTimeImmutable $initialInitialAssumeDeadAfter): void
    {
        $this->initialInitialAssumeDeadAfter = $initialInitialAssumeDeadAfter;
    }
}
