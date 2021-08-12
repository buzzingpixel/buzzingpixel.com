<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Uri
{
    /**
     * @Mapping\Column(
     *     name="uri",
     *     type="string",
     * )
     */
    protected string $uri = '';

    public function getHandle(): string
    {
        return $this->uri;
    }

    public function setHandle(string $uri): void
    {
        $this->uri = $uri;
    }
}
