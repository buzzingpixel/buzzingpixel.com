<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait ShortDescription
{
    /**
     * @Mapping\Column(
     *     name="short_description",
     *     type="text",
     * )
     */
    protected string $shortDescription = '';

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }
}
