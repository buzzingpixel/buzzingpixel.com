<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait Date
{
    /**
     * @Mapping\Column(
     *     name="date",
     *     type="datetimetz_immutable",
     *     nullable=false
     * )
     */
    protected DateTimeImmutable $date;

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }
}
