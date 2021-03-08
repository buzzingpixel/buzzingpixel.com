<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait MethodName
{
    /**
     * @Mapping\Column(
     *     name="method_name",
     *     type="string",
     * )
     */
    protected string $methodName = '';

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }
}
