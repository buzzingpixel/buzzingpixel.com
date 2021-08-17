<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsPublic
{
    /**
     * @Mapping\Column(
     *     name="is_public",
     *     type="boolean"
     * )
     */
    protected bool $isPublic = true;

    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }
}
