<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Status
{
    /**
     * @Mapping\Column(
     *     name="status",
     *     type="string",
     *     options={"default" : ""},
     * )
     */
    protected string $status = '';

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
