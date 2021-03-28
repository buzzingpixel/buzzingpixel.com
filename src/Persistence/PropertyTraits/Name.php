<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Name
{
    /**
     * @Mapping\Column(
     *     name="name",
     *     type="string",
     * )
     */
    protected string $name = '';

    public function getClassName(): string
    {
        return $this->name;
    }

    public function setClassName(string $name): void
    {
        $this->name = $name;
    }
}
