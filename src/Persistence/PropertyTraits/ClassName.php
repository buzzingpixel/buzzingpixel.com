<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait ClassName
{
    /**
     * @Mapping\Column(
     *     name="class_name",
     *     type="string",
     * )
     */
    protected string $className = '';

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): void
    {
        $this->className = $className;
    }
}
