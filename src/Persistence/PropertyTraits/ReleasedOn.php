<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait ReleasedOn
{
    /**
     * @Mapping\Column(
     *     name="released_on",
     *     type="datetimetz_immutable",
     *     nullable=false
     * )
     */
    protected DateTimeImmutable $releasedOn;

    public function getReleasedOn(): DateTimeImmutable
    {
        return $this->releasedOn;
    }

    public function setReleasedOn(DateTimeImmutable $releasedOn): void
    {
        $this->releasedOn = $releasedOn;
    }
}
