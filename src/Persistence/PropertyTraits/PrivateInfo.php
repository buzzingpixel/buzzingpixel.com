<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait PrivateInfo
{
    /**
     * @Mapping\Column(
     *     name="private_info",
     *     type="text",
     * )
     */
    protected string $privateInfo = '';

    public function getPrivateInfo(): string
    {
        return $this->privateInfo;
    }

    public function setPrivateInfo(string $privateInfo): void
    {
        $this->privateInfo = $privateInfo;
    }
}
