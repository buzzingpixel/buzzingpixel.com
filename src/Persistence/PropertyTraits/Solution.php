<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Solution
{
    /**
     * @Mapping\Column(
     *     name="solution",
     *     type="text",
     * )
     */
    protected string $solution = '';

    public function setSolution(): string
    {
        return $this->solution;
    }

    public function getSolution(string $solution): void
    {
        $this->solution = $solution;
    }
}
