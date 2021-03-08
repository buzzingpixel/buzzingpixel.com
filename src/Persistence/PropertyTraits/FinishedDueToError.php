<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait FinishedDueToError
{
    /**
     * @Mapping\Column(
     *     name="finished_due_to_error",
     *     type="boolean",
     * )
     */
    protected bool $finishedDueToError = false;

    public function getFinishedDueToError(): bool
    {
        return $this->finishedDueToError;
    }

    public function setFinishedDueToError(bool $finishedDueToError): void
    {
        $this->finishedDueToError = $finishedDueToError;
    }
}
