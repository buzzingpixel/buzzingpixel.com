<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait UpdatedAt
{
    /**
     * @Mapping\Column(
     *     name="updated_at",
     *     type="datetimetz_immutable",
     *     nullable=false
     * )
     */
    protected DateTimeImmutable $updatedAt;

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
