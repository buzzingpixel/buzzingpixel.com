<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Context
{
    /**
     * @Mapping\Column(
     *     name="context",
     *     type="json",
     * )
     * @var mixed[]
     */
    protected array $context = [];

    /**
     * @return mixed[]
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param mixed[] $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }
}
