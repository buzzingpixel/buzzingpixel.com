<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait OldOrderNumber
{
    /**
     * @Mapping\Column(
     *     name="old_order_number",
     *     type="string",
     * )
     */
    protected string $oldOrderNumber = '';

    public function getOldOrderNumber(): string
    {
        return $this->oldOrderNumber;
    }

    public function setOldOrderNumber(string $oldOrderNumber): void
    {
        $this->oldOrderNumber = $oldOrderNumber;
    }
}
