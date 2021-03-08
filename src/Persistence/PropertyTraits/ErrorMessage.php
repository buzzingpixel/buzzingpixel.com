<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait ErrorMessage
{
    /**
     * @Mapping\Column(
     *     name="error_message",
     *     type="text",
     * )
     */
    protected string $errorMessage = '';

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }
}
