<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IsAdmin
{
    /**
     * @Mapping\Column(
     *     name="is_admin",
     *     type="boolean",
     * )
     */
    protected bool $isAdmin = false;

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }
}
