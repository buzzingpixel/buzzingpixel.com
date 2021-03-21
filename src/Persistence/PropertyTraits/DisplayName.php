<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait DisplayName
{
    /**
     * @Mapping\Column(
     *     name="display_name",
     *     type="string",
     * )
     */
    protected string $displayName = '';

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }
}
