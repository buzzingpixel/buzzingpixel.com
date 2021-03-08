<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait AddedAt
{
    /**
     * @Mapping\Column(
     *     name="added_at",
     *     type="datetimetz_immutable",
     *     nullable=false
     * )
     */
    protected DateTimeImmutable $addedAt;

    public function getAddedAt(): DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(DateTimeImmutable $addedAt): void
    {
        $this->addedAt = $addedAt;
    }
}
