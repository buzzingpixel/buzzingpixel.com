<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait RunOrder
{
    /**
     * @Mapping\Column(
     *     name="run_order",
     *     type="integer",
     * )
     */
    protected int $runOrder = 1;

    public function getRunOrder(): int
    {
        return $this->runOrder;
    }

    public function setRunOrder(int $runOrder): void
    {
        $this->runOrder = $runOrder;
    }
}
